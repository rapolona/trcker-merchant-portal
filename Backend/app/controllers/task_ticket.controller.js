const { campaign_task_associations, tasks, task_ticket_audit } = require("../models");
const db = require("../models");
const Task_Ticket = db.task_tickets;
const Task_Ticket_Audit = db.task_ticket_audit;
const Task_Detail = db.task_details;
const User_Detail = db.userdetails;
const Task_Question = db.task_questions;
const Campaign = db.campaigns;
const Branches = db.branches;
const Op = db.Sequelize.Op;
const s3Utils = require("../utils/s3.utils.js");

// Update a Task_Ticketn by the id in the request
exports.approve = (req, res) => {
  const id = req.body.task_ticket_id;
  var statusToSet = "APPROVED"
  Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: ["Pending", "RESUBMISSION", "REJECTED"]}})
  .then(data => {
    if(data){
      var taskTicketAuditObj = {
        task_ticket_id: id,
        status_changed_to: statusToSet,
        last_updated_by: req.body.merchantid,
        last_updated_by_type: "MERCHANT",
      }
      db.sequelize.transaction({autocommit:false}, transaction => {
        var chainedPromise = []

        chainedPromise.push(
          Task_Ticket.update({approval_status: statusToSet}, {
            where: { task_ticket_id: id }
          })
            .then(num => {
              if (num != 1) {
                transaction.rollback();
                console.log("No Task Ticket Updated with id " + id)
                res.status(422).send({
                  message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
                });
              }
            })
            .catch(err => {
              console.log(`Error updating task ticket ${id} due to ${err}`)
              res.status(500).send({
                message: "Error updating Task Ticket with id=" + id
              });
            })
            );
        chainedPromise.push(
          Task_Ticket_Audit.create(taskTicketAuditObj)
          .catch(err => {
            console.log(`Error creating audit for ${id} due to ${err}`)
            res.status(500).send({
              message: "Error updating Task Ticket with id=" + id
            });
          })
          )
          return Promise.all(chainedPromise)
          .then(data => {
            res.send({message:"Task Ticket Updated Succesfully"})
          })
          .catch(err => {
            res.status(500).send({
              message: "Error updating Task Ticket with id=" + id
            });
          })
      })
    }
    else{
      res.status(422).send({
        message: "Cannot update the ticket repeatedly"
      })
    }
  })
  .catch(err => {
    res.status(500).send({
      message: "Something went wrong updating the ticket"
    })
  })
  };

  exports.reject = (req, res) => {
    const id = req.body.task_ticket_id;
    var statusToSet = "REJECTED"
    if(req.body.rejection_reason){var reason = req.body.rejection_reason}
    else{var reason = "None given"}
    Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: ["Pending", "APPROVED", "RESUBMISSION"]}})
    .then(data => {
      if(data){
        var taskTicketAuditObj = {
          task_ticket_id: id,
          status_changed_to: statusToSet,
          last_updated_by: req.body.merchantid,
          last_updated_by_type: "MERCHANT",
        }
        db.sequelize.transaction({autocommit:false}, transaction => {
          var chainedPromise = []
  
          chainedPromise.push(
            Task_Ticket.update({approval_status: statusToSet, rejection_reason:reason}, {
              where: { task_ticket_id: id }
            })
              .then(num => {
                if (num != 1) {
                  transaction.rollback();
                  console.log("No Task Ticket Updated with id " + id)
                  res.status(422).send({
                    message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
                  });
                }
              })
              .catch(err => {
                console.log(`Error updating task ticket ${id} due to ${err}`)
                res.status(500).send({
                  message: "Error updating Task Ticket with id=" + id
                });
              })
              );
          chainedPromise.push(
            Task_Ticket_Audit.create(taskTicketAuditObj)
            .catch(err => {
              console.log(`Error creating audit for ${id} due to ${err}`)
              res.status(500).send({
                message: "Error updating Task Ticket with id=" + id
              });
            })
            )
            return Promise.all(chainedPromise)
            .then(data => {
              res.send({message:"Task Ticket Updated Succesfully"})
            })
            .catch(err => {
              res.status(500).send({
                message: "Error updating Task Ticket with id=" + id
              });
            })
        })
      }
      else{
        res.status(422).send({
          message: "Cannot update the ticket repeatedly"
        })
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Something went wrong updating the ticket"
      })
    })
    };


    exports.findTicketsByUser = (req, res) => {      
      const campaign_id  = req.body.campaign_id;
      Task_Ticket.findAll({
        where: {campaign_id: campaign_id},
        include: [
          {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email','settlement_account_number', 'settlement_account_type']}
        ],
        attributes: ["user_id"],
        group: ["user_id"]       
      })
      .then(data => {
        res.send(data)
      })
      .catch(err => {
        res.status(500).send({
          message: err || "Error retrieving users with tickets"
        })
      })
    }

    exports.findTicketsOfUser = (req, res) => {
      const id = req.body.campaign_id
      const userid = req.body.user_id
       //TO DO: Refactor
      Task_Ticket.findAll({
        where: {campaign_id : id, user_id: userid}, 
        include: [
          {model: Task_Detail, include: [
            {model:Task_Question, attributes: ['question']}]
          },
          {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email','settlement_account_number', 'settlement_account_type']}
        ]
        })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        console.log(err)
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task tickets."
        });
      });
    }

  exports.findAllTicketsWithDetails = (req,res)=> {
    const id = req.body.merchantid
    var task_ticket_condition = {}// For status & date of submission
    var campaign_condition = {merchant_id:id} // For campaign name
    var user_detail_condition = {} //For respondent email or name


    var page_number = 1;
    var count_per_page = null;

    if((req.query.page)&&(req.query.count_per_page)){
      page_number = parseInt(req.query.page);
      count_per_page = parseInt(req.query.count_per_page);  
    }
  
    var skip_number_of_items = (page_number * count_per_page) - count_per_page
   

    //Build filter conditions
    //Build condition for user detail
    if(req.query.respondent){
      user_detail_condition = {
        [Op.or]:[
        {first_name: { [Op.like]: `%${req.query.respondent}%` }},
        {last_name: { [Op.like]: `%${req.query.respondent}%` }},
        {email: { [Op.like]: `%${req.query.respondent}%` }}
      ]}
    }
    //Build condition for campaign
    if(req.query.campaign_name){
      campaign_condition.campaign_name = { [Op.like]: `%${req.query.campaign_name}%` } ; //Searching by campaign name
    }
    //Build condition for task ticket
    if(req.query.status){
      task_ticket_condition.approval_status = { [Op.like]: `%${req.query.status}%` } //Search by approval status
    }
    // Search by submission date 
    if(req.query.submission_date_start && req.query.submission_date_end){
      task_ticket_condition.createdAt = {[Op.gte]: req.query.submission_date_start,[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
    } 
    else {
      if(req.query.submission_date_start){
        task_ticket_condition.createdAt= {[Op.gte]: req.query.submission_date_start};
      }
      if(req.query.submission_date_end){
        task_ticket_condition.createdAt= {[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
      }
    }
    if(req.query.campaign_id){
      task_ticket_condition.campaign_id = req.query.campaign_id
    }



    Task_Ticket.findAndCountAll({
      offset:skip_number_of_items, limit: count_per_page,distinct:true,subQuery:false,
      where:task_ticket_condition,
      include: [
        {model: tasks, attributes: ['task_name']},
        {model: User_Detail, as:'user_detail', where:user_detail_condition,attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
        {model: Campaign, as:'campaign', where:campaign_condition, attributes:['campaign_id','campaign_name'],
      include:{model:campaign_task_associations,where:{task_id: {[Op.col]: 'task_ticket.task_id' } }, attributes: ['task_id','reward_amount']}}
      ],
      order: [["createdAt", "DESC"]]
      })
    .then(data => {
      if(data.rows[0]){
        var dataResp = {}
        dataResp.total_pages = Math.ceil(data.count/count_per_page);
        dataResp.current_page = page_number;   
        dataResp.count_per_page = count_per_page
        var dataObj = []
        dataObj.total_pages = Math.ceil(data.count/count_per_page);
        dataObj.current_page = page_number;  
        
        data.rows.forEach((element,element_index) => {
          dataObj.push(element.get({plain:true}))
          //Loop below is for iterating through each task within task_details.
          dataObj[element_index].reward_amount = element.campaign.campaign_task_associations[0].reward_amount
          dataObj[element_index].task_name = element.task.task_name;
          delete dataObj[element_index].task
          delete dataObj[element_index].campaign.campaign_task_associations
          
        })
        dataResp.rows=dataObj;
      }
      res.send(dataResp);
    })
    .catch(err => {
      console.log(err)
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving task tickets."
      });
    });
    }

    exports.findAllTicketsForReport = (req,res)=> {
      const id = req.body.merchantid
      var task_ticket_condition = {}// For status & date of submission
      var campaign_condition = {merchant_id:id} // For campaign name
      var user_detail_condition = {} //For respondent email or name

      if(req.query.respondent){
        user_detail_condition = {
          [Op.or]:[
          {first_name: { [Op.like]: `%${req.query.respondent}%` }},
          {last_name: { [Op.like]: `%${req.query.respondent}%` }},
          {email: { [Op.like]: `%${req.query.respondent}%` }}
        ]}
      }
      //Build condition for campaign
      if(req.query.campaign_name){
        campaign_condition.campaign_name = { [Op.like]: `%${req.query.campaign_name}%` } ; //Searching by campaign name
      }
      //Build condition for task ticket
      if(req.query.status){
        task_ticket_condition.approval_status = { [Op.like]: `%${req.query.status}%` } //Search by approval status
      }
      // Search by submission date 
      if(req.query.submission_date_start && req.query.submission_date_end){
        task_ticket_condition.createdAt = {[Op.gte]: req.query.submission_date_start,[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
      } 
      else {
        if(req.query.submission_date_start){
          task_ticket_condition.createdAt= {[Op.gte]: req.query.submission_date_start};
        }
        if(req.query.submission_date_end){
          task_ticket_condition.createdAt= {[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
        }
      }
      if(req.query.campaign_id){
        task_ticket_condition.campaign_id = req.query.campaign_id
      }

     
      Task_Ticket.findAll({
        attributes: ['campaign_id','task_ticket_id','device_id','approval_status','createdAt','updatedAt'],
        where: task_ticket_condition,
        include: [
          {model: Task_Detail, as:'task_details',
            where:{ //This filters out all base64 image
              [Op.and]: [
                {response: {[Op.notLike]: 'data:image%'}},
                db.Sequelize.where(db.Sequelize.fn('char_length', db.Sequelize.col('response')), {
                  [Op.lt]: 1000
                })
              ]
            },
            attributes:['response'],
            include: [
              {model:Task_Question, as: 'task_question', attributes: ['question'] ,
                include: {model:tasks, as: 'task', attributes:['task_name','task_id'], 
                   },  }, //where:{campaign_id: {[Op.col]: 'task_ticket.campaign_id'}}
            ]
          },
          {model: Branches, attributes:['name','address','city']},
          {model: User_Detail, as:'user_detail', where: user_detail_condition, attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
          {model: Campaign, as:'campaign',where:campaign_condition, attributes:['campaign_id','campaign_name'], include: [{model:campaign_task_associations, attributes: ['reward_amount','task_id']} ]}
        ],
        order: [["createdAt", "DESC"]]
        })
      .then(data => {
        if(data[0]){
          var dataObj = []
          data.forEach((element,element_index) => {
            dataObj.push(element.get({plain:true}))
            //Loop below is for iterating through each task within task_details.
            for (detail_index = 0; detail_index < dataObj[element_index].task_details.length; detail_index++) {
              dataObj[element_index].task_details[detail_index].task_question.task_name = dataObj[element_index].task_details[detail_index].task_question.task.task_name
              
              //Loop below is for iterating through campaign_task_associations. We look for a match in task_id and insert the correct reward amount.
              for (reward_index = 0; reward_index < dataObj[element_index].campaign.campaign_task_associations.length; reward_index++) {
              if(dataObj[element_index].campaign.campaign_task_associations[reward_index].task_id==dataObj[element_index].task_details[detail_index].task_question.task.task_id ){}
                //Insert matched reward amount
                dataObj[element_index].task_details[detail_index].task_question.reward_amount = dataObj[element_index].campaign.campaign_task_associations[reward_index].reward_amount;
              }
              delete dataObj[element_index].task_details[detail_index].task_question.task

            }
            delete dataObj[element_index].campaign.campaign_task_associations
            
          })

        }
        res.send(dataObj);
      })
      .catch(err => {
        console.log(err)
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task tickets."
        });
      });
      }

    exports.getNextAndPrev = (req,res) => {
      const id = req.body.merchantid
      var task_ticket_id = req.body.task_ticket_id || req.query.task_ticket_id
      var task_ticket_condition = {}// For status & date of submission
      var campaign_condition = {merchant_id:id} // For campaign name
      var user_detail_condition = {} //For respondent email or name
      var filterObj = {}

      if(req.query.respondent){
        user_detail_condition = {
          [Op.or]:[
          {first_name: { [Op.like]: `%${req.query.respondent}%` }},
          {last_name: { [Op.like]: `%${req.query.respondent}%` }},
          {email: { [Op.like]: `%${req.query.respondent}%` }}
        ]}
        filterObj.respondent = req.query.respondent
      }
      //Build condition for campaign
      if(req.query.campaign_name){
        campaign_condition.campaign_name = { [Op.like]: `%${req.query.campaign_name}%` } ; //Searching by campaign name
        filterObj.campaign_name = req.query.campaign_name
      }
      //Build condition for task ticket
      if(req.query.status){
        task_ticket_condition.approval_status = { [Op.like]: `%${req.query.status}%` } //Search by approval status
        filterObj.status = req.query.status
      }
      // Search by submission date 
      if(req.query.submission_date_start && req.query.submission_date_end){
        task_ticket_condition.createdAt = {[Op.gte]: req.query.submission_date_start,[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
        filterObj.submission_date_start = req.query.submission_date_start;
        filterObj.submission_date_end = req.query.submission_date_end;
      } 
      else {
        if(req.query.submission_date_start){
          task_ticket_condition.createdAt= {[Op.gte]: req.query.submission_date_start};
          filterObj.submission_date_start = req.query.submission_date_start;
        }
        if(req.query.submission_date_end){
          task_ticket_condition.createdAt= {[Op.lte]: req.query.submission_date_end+' 23:59:00.000Z'};
          filterObj.submission_date_end = req.query.submission_date_end;
        }
      }
      if(req.query.campaign_id){
        task_ticket_condition.campaign_id = req.query.campaign_id
        filterObj.campaign_id = req.query.campaign_id
      }

      if((req.query.page)&&(req.query.count_per_page)){
        page_number = parseInt(req.query.page);
        count_per_page = parseInt(req.query.count_per_page);  
      }
      var skip_number_of_items = (page_number * count_per_page) - count_per_page

      Task_Ticket.findAndCountAll({
        offset:skip_number_of_items, limit: count_per_page,distinct:true,
        where:task_ticket_condition,
        include: [
          {model: tasks, attributes: ['task_name']},
          {model: User_Detail, as:'user_detail', where:user_detail_condition,attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
          {model: Campaign, as:'campaign', where:campaign_condition, attributes:['campaign_id','campaign_name']}
        ],
        attributes:["task_ticket_id", "campaign_id", "createdAt"],
        order: [["createdAt", "DESC"]]
        })
        .then(data => {
          if(data){
            var dataObj = []
            var nextAndPrevData = {}
            data.rows.forEach((element,element_index) => {
              dataObj.push(element.get({plain:true}))
            })
            data.total_pages = Math.ceil(data.count/count_per_page);
            data.current_page = page_number;  
            var currentIndex = dataObj.findIndex(element => {
              console.log(element)
              if(element.task_ticket_id == task_ticket_id){
                return true
              }
            })
            nextAndPrevData.filter = filterObj

            console.log("Current Index is " + currentIndex)
            console.log("Current page is " + page_number)
            if(currentIndex == (dataObj.length-1) && page_number != data.total_pages){
              skip_number_of_items = ((page_number+1)* count_per_page) - count_per_page
              Task_Ticket.findAndCountAll({
                offset:skip_number_of_items, limit: count_per_page,distinct:true,
                where:task_ticket_condition,
                include: [
                  {model: tasks, attributes: ['task_name']},
                  {model: User_Detail, as:'user_detail', where:user_detail_condition,attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
                  {model: Campaign, as:'campaign', where:campaign_condition, attributes:['campaign_id','campaign_name'],
                include:{model:campaign_task_associations,where:{task_id: {[Op.col]: 'task_ticket.task_id' } }, attributes: ['task_id','reward_amount']}}
                ],
                attributes:["task_ticket_id", "campaign_id","createdAt"],
                order: [["createdAt", "DESC"]]
                })
                .then(nextPageData => {
                  nextAndPrevData.current_page = page_number;   
                  nextAndPrevData.count_per_page = count_per_page
                  nextAndPrevData.next = nextPageData.rows[0]
                  nextAndPrevData.next.page = page_number+1
                  nextAndPrevData.prev = dataObj[currentIndex-1]
                  nextAndPrevData.prev.page = page_number                  
                  res.send(nextAndPrevData)
                })
            }
            else if (currentIndex == 0 && page_number !=1) {
              skip_number_of_items =((page_number-1) * count_per_page) - count_per_page
              Task_Ticket.findAndCountAll({
                offset:skip_number_of_items, limit: count_per_page,distinct:true,
                where:task_ticket_condition,
                include: [
                  {model: tasks, attributes: ['task_name']},
                  {model: User_Detail, as:'user_detail', where:user_detail_condition,attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
                  {model: Campaign, as:'campaign', where:campaign_condition, attributes:['campaign_id','campaign_name'],
                include:{model:campaign_task_associations,where:{task_id: {[Op.col]: 'task_ticket.task_id' } }, attributes: ['task_id','reward_amount']}}
                ],
                attributes:["task_ticket_id", "campaign_id", "createdAt"],
                order: [["createdAt", "DESC"]]
                })
                .then(prevPageData => {
                  nextAndPrevData.current_page = page_number;   
                  nextAndPrevData.count_per_page = count_per_page
                  nextAndPrevData.next = dataObj[currentIndex+1]
                  nextAndPrevData.next.page = page_number
                  nextAndPrevData.prev = prevPageData.rows[prevPageData.rows.length-1]
                  nextAndPrevData.prev.page = page_number-1
                  res.send(nextAndPrevData)
                })  

            }
            else{
              if(currentIndex == 0){
                nextAndPrevData.current_page = page_number;   
                nextAndPrevData.count_per_page = count_per_page
                if(dataObj[currentIndex+1]){
                  nextAndPrevData.next = dataObj[currentIndex+1]
                  nextAndPrevData.next.page = page_number
                }
              }
              else if(currentIndex == dataObj.length-1){
                nextAndPrevData.current_page = page_number;   
                nextAndPrevData.count_per_page = count_per_page
                nextAndPrevData.prev = dataObj[currentIndex-1]
                nextAndPrevData.prev.page = page_number
              }
              else{
                nextAndPrevData.current_page = page_number;   
                nextAndPrevData.count_per_page = count_per_page
                nextAndPrevData.next = dataObj[currentIndex+1]
                nextAndPrevData.next.page = page_number
                nextAndPrevData.prev = dataObj[currentIndex-1]
                nextAndPrevData.prev.page = page_number
              }
              res.send(nextAndPrevData)
            }
          }
        })

    }

exports.getCampaignGallery = (req,res) => {
  var campaign_id = req.query.campaign_id
  var resultUrls= []
  var resultObj = {}
  var chainedPromise = []
  var page_number = 1
  var count_per_page = 25
  if((req.query.page)&&(req.query.count_per_page)){
    page_number = parseInt(req.query.page);
    count_per_page = parseInt(req.query.count_per_page);  
  }
  var skip_number_of_items = (page_number * count_per_page) - count_per_page
  Task_Ticket.findAndCountAll({
    where: {campaign_id: campaign_id}, include: [{model:Task_Detail, include:[{model:Task_Question, where:{required_inputs: "IMAGE"}}]}],
    limit:count_per_page, offset:skip_number_of_items
  })
  .then(data =>{
    resultObj.total_pages = Math.ceil(data.count/count_per_page);
    resultObj.current_page = page_number;
    resultObj.count = data.count  
    var dataObj = []
    data.rows.forEach(element=>{
      dataObj.push(element.get({plain:true}))
    })
    dataObj.map(element => {
      element.task_details.forEach(taskDetail => {
        chainedPromise.push(
          s3Utils.s3getHeadObject("trcker-task-ticket-images", campaign_id+"/"+taskDetail.response)
          .then(data => {
            resultUrls.push(s3Utils.s3GetSignedURL("trcker-task-ticket-images", campaign_id+"/"+taskDetail.response)) 
            })
            .catch(err => {
              console.log(err)
              res.status(500).send({
                message: err.code
              });
            })
          )
      })
    })
    chainedPromise.push(
      Campaign.findOne({where: {campaign_id:campaign_id}})
      .catch(err => {
        console.log(err)
        res.status(500).send({message: "Error retrieving campaign"})
      })
    )
    Promise.all(chainedPromise)
    .then(data => {
      resultObj.rows = resultUrls
      if(data[1]){
        resultObj.campaign_details = data[1]
      }
      res.send(resultObj)
    })
    .catch(err => {
      res.status(500).send({
        message: "Something went wrong retrieving the s3 urls"
      })
    })
  })
  .catch(err => {
    console.log(err)
    res.status(500).send({
      message: "Something went wrong retrieving task tickets"
    })
  })


}