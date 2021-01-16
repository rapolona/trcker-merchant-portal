const { campaign_task_associations, tasks } = require("../models");
const db = require("../models");
const Task_Ticket = db.task_tickets;
const Task_Detail = db.task_details;
const User_Detail = db.userdetails;
const Task_Question = db.task_questions;
const Campaign = db.campaigns;
const Branches = db.branches;
const Op = db.Sequelize.Op;

// Update a Task_Ticketn by the id in the request
exports.approve = (req, res) => {
  const id = req.body.task_ticket_id;
  var statusToSet = "APPROVED"
  Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: "Pending"}})
  .then(data => {
    if(data){
      Task_Ticket.update({approval_status: statusToSet}, {
        where: { task_ticket_id: id }
      })
        .then(num => {
          if (num == 1) {
            res.send({
              message: "Task Ticket was updated successfully."
            });
          } else {
            res.status(422).send({
              message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
            });
          }
        })
        .catch(err => {
          res.status(500).send({
            message: "Error updating Task Ticket with id=" + id
          });
        });
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
    Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: "Pending"}})
    .then(data => {
      if(data){
        Task_Ticket.update({approval_status: statusToSet, rejection_reason:reason}, {
          where: { task_ticket_id: id }
        })
          .then(num => {
            if (num == 1) {
              res.send({
                message: "Task Ticket was updated successfully."
              });
            } else {
              res.status(422).send({
                message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
              });
            }
          })
          .catch(err => {
            res.status(500).send({
              message: "Error updating Task Ticket with id=" + id
            });
          });
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
    const condition = req.query

    var page_number = 1;
    var count_per_page = 25;

    if((req.query.page)&&(req.query.count_per_page)){
      page_number = parseInt(req.query.page);
      count_per_page = parseInt(req.query.count_per_page);  
    }
  
    var skip_number_of_items = (page_number * count_per_page) - count_per_page
   
    Task_Ticket.findAndCountAll({
      offset:skip_number_of_items, limit: count_per_page,distinct:true,
      include: [
        {model: Task_Detail, as:'task_details', attributes:['createdAt'],include: [{
          model:Task_Question, as: 'task_question', attributes: ['question'], 
          include:{model: tasks, attributes:['task_id']}},
          ]
        },
        {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
        {model: Campaign, as:'campaign', where:{merchant_id : id}, attributes:['campaign_id','campaign_name'],
      include:{model:campaign_task_associations,where:{}, attributes: ['task_id','reward_amount']}}
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
     
      Task_Ticket.findAll({
        attributes: ['campaign_id','task_ticket_id','device_id','approval_status','createdAt','updatedAt'],
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
          {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email', 'settlement_account_number', 'settlement_account_type']},
          {model: Campaign, as:'campaign',where:{merchant_id : id}, attributes:['campaign_id','campaign_name'], include: [{model:campaign_task_associations, attributes: ['reward_amount','task_id']} ]}
        ],
        order: [["createdAt", "DESC"]]
        })
      .then(data => {
        console.log(data)
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