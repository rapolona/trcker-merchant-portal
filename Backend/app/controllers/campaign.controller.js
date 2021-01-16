const db = require("../models");
const moment = require("moment");
const { branches, tasks, campaign_branch_associations, campaign_task_associations } = require("../models");
const Campaign = db.campaigns;
const Branch = db.branches;
const Task_Questions = db.task_questions;
const Task_Question_Choices = db.task_question_choices;
const Campaign_Branch_Association = db.campaign_branch_associations;
const Campaign_Task_Association = db.campaign_task_associations;
const Campaign_Reward = db.campaign_rewards;
const Task_Ticket = db.task_tickets;
const Op = db.Sequelize.Op;
const sequelize = db.sequelize;





// Create and Save a new Campaign
exports.create = (req, res) => {
    // Validate request
    if (!req.body.start_date) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
    if (moment(req.body.end_date).isBefore(moment(req.body.start_date).subtract(0,'days'))){
      res.status(400).send({
        message: "Cannot create campaign whose end date occurs before the start date"
      });
      return;
    }
    if (moment(req.body.end_date).isBefore(moment(Date.now()))){
      res.status(400).send({
        message: "Cannot create campaign whose end date has already passed"
      });
      return;
    }
    if (moment(req.body.start_date).isBefore(moment(Date.now()).subtract(1,'days'))){
      res.status(400).send({
        message: "Cannot create campaign whose start date has already passed"
      });
      return;
    }


    console.log(req.body)
    // Create a campaign
    const campaign = {
        merchant_id: req.body.merchantid,
        start_date: req.body.start_date,
        end_date: req.body.end_date,
        campaign_name: req.body.campaign_name,
        campaign_description: req.body.campaign_description,
        budget: req.body.budget,
        audience_age_min: req.body.audience_age_min,
        audience_age_max: req.body.audience_age_max,
        audience_gender: req.body.audience_gender,
        allowed_account_level: req.body.allowed_account_level,
        super_shoppers: req.body.super_shoppers,
        allow_everyone: req.body.allow_everyone,
        status: req.body.status,
        task_type: req.body.task_type
    };

    console.log(campaign)
  
    // Save Campaign in the database
    Campaign.create(campaign)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Campaign."
        });
      });
  };

  // Create and Save a new Campaign
  exports.createCustom = (req, res) => {
    // Validate request
    if (!req.body.start_date) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
    if (moment(req.body.end_date).isBefore(moment(req.body.start_date).subtract(0,'days'))){
      res.status(422).send({
        message: "Cannot create campaign whose end date occurs before the start date"
      });
      return;
    }
    const branches_container = []
    var campaign_status = "INACTIVE";
    var at_home_campaign = req.body.at_home_campaign;

    if(at_home_campaign==true){
      var at_home_respondent_count=req.body.at_home_respondent_count;
      var at_home_branch_id = "fbe9b0cf-5a77-4453-a127-9a8567ff3aa7";
      branches_container.push({"branch_id":at_home_branch_id, "respondent_count":at_home_respondent_count});
    }
    else{
      at_home_campaign=false
      for(i=0;i<req.body.branches.length;i++){
        current_item = req.body.branches[i]
        if(!current_item.branch_id){
          current_item.branch_id = "fbe9b0cf-5a77-4453-a127-9a8567ff3aa7"
        }
        branches_container.push(req.body.branches[i])
      }
    }
    // if (moment(req.body.end_date).isBefore(moment(Date.now()).subtract(1,'days'))){
    //   res.status(400).send({
    //     message: "Cannot create campaign whose end date has already passed"
    //   });
    //   return;
    // }
    // if (moment(req.body.start_date).isBefore(moment(Date.now()).subtract(1,'days'))){
    //   res.status(400).send({
    //     message: "Cannot create campaign whose start date has already passed"
    //   });
    //   return;
    // }

  // Create a campaign
  
  for(i=0;i<req.body.tasks.length; i++){
    req.body.tasks[i].index = i+1;
  }
  for(i=0;i<branches_container.length;i++){
    branches_container[i]["submitted_response_count"]=0;
    branches_container[i]["status"]=0
  }
  var total_reward_amount = 0;
  for(i=0;i<req.body.tasks.length;i++){
    total_reward_amount = total_reward_amount + parseFloat(req.body.tasks[i].reward_amount)
  }

  //Sets status to ongoing if current date lies between start & end date
  var time_to_check = moment()
  
  if(time_to_check>=Date.parse(req.body.start_date) && time_to_check <= Date.parse(req.body.end_date)){
    campaign_status = "ONGOING";
  }

  const campaign = {
      merchant_id: req.body.merchantid,
      start_date: req.body.start_date,
      end_date: req.body.end_date + ' 23:59:00.000Z',
      budget: req.body.budget,
      total_reward_amount: total_reward_amount,
      campaign_name: req.body.campaign_name,
      campaign_description: req.body.campaign_description,
      thumbnail_url: req.body.thumbnail_url,
      description_image_url: req.body.description_image_url,
      audience_age_min: req.body.audience_age_min,
      audience_age_max: req.body.audience_age_max,
      audience_gender: req.body.audience_gender,
      allowed_account_level: req.body.allowed_account_level,
      super_shoppers: req.body.super_shoppers,
      allow_everyone: req.body.allow_everyone,
      status: campaign_status,
      at_home_campaign: at_home_campaign,
      campaign_type: req.body.campaign_type,
      campaign_task_associations: req.body.tasks,
      campaign_branch_associations: branches_container
  };
  if(campaign.allow_everyone){
    campaign.allowed_account_level='any'
  }

  console.log(campaign)



db.sequelize.transaction(transaction =>
  Campaign.create(campaign, {include: [
    {model:Campaign_Task_Association, as:"campaign_task_associations"},
    {model:Campaign_Branch_Association, as:"campaign_branch_associations"},
    {model:Campaign_Reward, as:"campaign_reward"}
  ],
    transaction
  }).then(data => {
    res.send(data);
  })
  .catch(err => {
    transaction.rollback();
    res.status(500).send({
      message:
        err.message || "Some error occurred while creating the Campaign."
    });
  })
)
};

// Retrieve all Campaigns from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Campaign.findAll({ where: condition,raw: true, nest:true})
      .then(data => {

        console.log(data)
        
        res.json(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving campaigns."
        });
      });
  };

  exports.findAllMerchant = (req, res) => {
    const merchant_id = req.body.merchantid;
    console.log("ding")
    console.log(req.body)

  
    Campaign.findAll({ where: {merchant_id: merchant_id} , include: [Branch]})
      .then(data => {
        console.log(data)   
        res.json(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving campaigns."
        });
      });
  };
  exports.findAllCustom = (req, res) => {
    const id = req.body.merchantid;
    var condition = req.query;
    

    if((req.query.page)&&(req.query.count_per_page)){
      var page_number = parseInt(req.query.page);
      var count_per_page = parseInt(req.query.count_per_page);
      var skip_number_of_items = (page_number * count_per_page) - count_per_page
      

      Campaign.findAndCountAll({ offset:skip_number_of_items, limit: count_per_page ,attributes:{exclude:['thumbnail_url']},where: {merchant_id: id} , order:[["createdAt", "DESC"]]})
      .then(data => {
        data.total_pages = Math.ceil(data.count/count_per_page);
        data.current_page = page_number;   
        res.json(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving campaigns."
        });
      });
    }
    else{
      Campaign.findAll({ attributes:{exclude:['thumbnail_url']},where: {merchant_id: id} , order:[["createdAt", "DESC"]]})
      .then(data => { 
        res.json(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving campaigns."
        });
      });
    }



  

  };

// Find a single Campaign with an id
exports.findOne = (req, res) => {
  const campaign_id = req.params.campaign_id;
  const merchant_id = req.body.merchantid;
  var condition = { 
    where: 
    {
      merchant_id: merchant_id, 
      campaign_id: campaign_id
    } , 
    include: [
      {model:Branch, attributes:['branch_id'], through: {attributes: ['respondent_count']} },
      {model:tasks, attributes:['task_id'], through: {attributes: ['reward_amount']}}

    ],
    attributes: { exclude: ['total_reward_amount','createdAt','updatedAt','merchant_id','campaign_id']}
  };

    Campaign.findOne(condition)
      .then(data => {
        
        new_result = data.get({plain:true});
        for (i = 0; i < new_result.branches.length; i++){
          new_result.branches[i].respondent_count = new_result.branches[i].campaign_branch_association.respondent_count;
          delete new_result.branches[i].campaign_branch_association;
        }
        for (i = 0; i < new_result.tasks.length; i++){
          new_result.tasks[i].reward_amount = new_result.tasks[i].campaign_task_association.reward_amount;
          delete new_result.tasks[i].campaign_task_association;
        }
        new_result.start_date = new_result.start_date.toISOString().substring(0,10);
        new_result.end_date = new_result.end_date.toISOString().substring(0,10);




        res.send(new_result);

      })
      .catch(err => {
        res.status(500).send(err);
      });
  };

// Update a Campaign by the id in the request
exports.update = (req, res) => {
    const id = req.body.campaign_id;
    if(req.body.tasks){
      for(var i = 0; i < req.body.tasks.length; i++){
        req.body.tasks[i].campaign_id = id
        req.body.tasks[i].index = i+1;
      }
    }
    if(req.body.branches){
      for(var i=0; i<req.body.branches.length;i++){
        req.body.branches[i].campaign_id = id
      }
    }
    if(req.body.at_home_campaign){
      req.body.branches = [];
      var at_home_respondent_count=req.body.at_home_respondent_count;
      var at_home_branch_id = "fbe9b0cf-5a77-4453-a127-9a8567ff3aa7";
      req.body.branches.push({"campaign_id":id ,"branch_id":at_home_branch_id, "respondent_count":at_home_respondent_count});
    }
    var total_reward_amount = 0;
    for(i=0;i<req.body.tasks.length;i++){
      total_reward_amount = total_reward_amount + parseFloat(req.body.tasks[i].reward_amount)
    }
    var campaignBody = {
      start_date: req.body.start_date,
      end_date: req.body.end_date + ' 23:59:00.000Z',
      budget: req.body.budget,
      total_reward_amount: total_reward_amount,
      campaign_name: req.body.campaign_name,
      campaign_description: req.body.campaign_description,
      thumbnail_url: req.body.thumbnail_url,
      description_image_url: req.body.description_image_url,
      audience_age_min: req.body.audience_age_min,
      audience_age_max: req.body.audience_age_max,
      audience_gender: req.body.audience_gender,
      allowed_account_level: req.body.allowed_account_level,
      super_shoppers: req.body.super_shoppers,
      allow_everyone: req.body.allow_everyone,
      at_home_campaign: req.body.at_home_campaign,
      at_home_respondent_count: req.body.at_home_respondent_count,
      campaign_type: req.body.campaign_type,
    }


    
    Campaign.update(campaignBody, {where: { campaign_id: id, merchant_id : req.body.merchantid, status:{[Op.or]:["INACTIVE", "DISABLED"]}},})
    .then(num => {
      if(num == 1){
        db.sequelize.transaction({autocommit:false},transaction => {
          var campaignUpdateTransactions = [
            Campaign_Task_Association.destroy({
              where: {campaign_id: id},
              transaction:transaction}),
            Campaign_Task_Association.bulkCreate(req.body.tasks, {transaction:transaction}),
              Campaign_Branch_Association.destroy({
                where: {campaign_id: id},
                transaction:transaction
              }),
              Campaign_Branch_Association.bulkCreate(req.body.branches, {transaction:transaction})
          ]
          return Promise.all(campaignUpdateTransactions)
        })
        .then(data => {
          if(data){
            res.send({
              message: "Campaign, Tasks, Rewards, and Branches are updated succesfully"
            })
          }
        })
        .catch(err => {
          res.status(500).send({
            message: err.message || "Error updating campaign"
          })
        })
      }
      else{
        res.status(422).send({message:"Cannot update campaign that is ongoing"})
        return;
      }
    })  
  };

// Delete a Campaign with the specified id in the request
exports.delete = (req, res) => {
  const campaign_id = req.body.campaign_id;
  const merchant_id = req.body.merchantid

  Campaign.destroy({
    where: { campaign_id: campaign_id, merchant_id: merchant_id }
  })
    .then(num => {
      if (num == 1) {
        res.send({
          message: "Campaign was deleted successfully!"
        });
      } else {
        res.status(422).send({
          message: `Cannot delete Campaign with id=${campaign_id}. Maybe Campaign was not found!`
        });
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Could not delete Campaign with id=" + campaign_id
      });
    });
};

// Update a Campaign by the id in the request
exports.extendCampaign = (req, res) => {
  const campaign_id = req.body.campaign_id;
  const merchant_id = req.body.merchantid;

  //var time_to_check = moment()

  if (moment(req.body.end_date).isBefore(moment(Date.now()).subtract(1,'days'))){
    res.status(422).send({
      message: "Cannot extend end date to a date that has already passed"
    });
  return;
  }

  var campaignBody = {
    end_date: req.body.end_date + ' 23:59:00.000Z',
    status: "ONGOING"
  }



    Campaign.update(campaignBody, {
      where: { merchant_id: merchant_id, campaign_id: campaign_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign was updated successfully."
          });
        } else {
          res.status(422).send({
            message: `Error updating Campaign with id=${campaign_id}.`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: err.message || "Error updating Campaign with id=" + campaign_id
        });
      });
  };


  exports.countRespondents = (req,res) => {
    var groupByParam = req.query.groupby
    var groupArr = ['campaign_id']
    var taskTicketAttributesArr = [[db.Sequelize.fn("COUNT", "user_id"), "respondents"], 'campaign_id']
    var includeArr = [{model:Campaign, where:{merchant_id: req.body.merchantid}, attributes:['campaign_name', 'campaign_description']}]
    if(groupByParam){
      groupByParam = groupByParam.split(",")
      for(var i = 0; i<groupByParam.length; i++){
        if(groupByParam[i] == "BRANCH"){
          groupArr.push('task_ticket.branch_id')
          includeArr.push({model:Branch, attributes:['name']})
        }
        if(groupByParam[i] == "CAMPAIGN"){
          groupArr.push('task_ticket.campaign_id')
        }
        if(groupByParam[i] == "STATUS"){
          groupArr.push('task_ticket.approval_status')
          taskTicketAttributesArr.push("approval_status")
        }
      }
    }
    
    Task_Ticket.findAll({include:includeArr,group:groupArr, attributes:taskTicketAttributesArr})
    .then(data => {
      res.send(data)
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while counting respondents"
      });
    })
  }


  exports.getActiveCampaigns = (req,res) => {
    const merchantId = req.body.merchantid;
    var groupByParams = req.query.groupby;
    var groupArr = []
    var attributesArr = [[db.Sequelize.fn('COUNT','campaign_id'), "active_campaigns"]]
    if(groupByParams && groupByParams == "campaign_type"){
       groupArr.push("campaign_type");
       attributesArr.push("campaign_type")
    }

    Campaign.findAll({where: {merchant_id: merchantId, status: "ONGOING"}, group:groupArr,raw:true,attributes:attributesArr})
    .then(data => {
      res.send(data)
    })
    .catch(err=>{
      res.status(500).send({
        message:
          err.message || "Error counting campaign"
      });
    })
  }


    // Find a single Campaign with an id
exports.enable_campaign = (req, res) => {
  const campaign_id = req.params.campaign_id;
  const merchant_id = req.body.merchantid;

  Campaign.update({status:"ONGOING"}, {
    where: { merchant_id: merchant_id, 
             campaign_id: campaign_id,
             status: "DISABLED" }
  })
    .then(num => {
      if (num == 1) {
        res.send({
          message: "Campaign was set to ongoing successfully"
        });
      } else {
        res.status(500).send({
          message: `Cannot update Campaign with id=${campaign_id}. Maybe Campaign has already lapsed or has not been paused yet.`
        });
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Error updating Campaign with id=" + campaign_id
      });
    });
};

exports.disable_campaign = (req, res) => {
  const campaign_id = req.params.campaign_id;
  const merchant_id = req.body.merchantid;

  Campaign.update({status:"DISABLED"}, {
    where: { merchant_id: merchant_id, 
             campaign_id: campaign_id,
             status: "ONGOING" }
  })
    .then(num => {
      if (num == 1) {
        res.send({
          message: "Campaign was set to disabled successfully"
        });
      } else {
        res.status(500).send({
          message: `Cannot update Campaign with id=${campaign_id}. Maybe Campaign has already lapsed or has not been paused yet.`
        });
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Error updating Campaign with id=" + campaign_id
      });
    });
};

exports.countCampaign = (req,res)=>{
  const merchantId = req.body.merchantid;
  var groupByParams = req.query.groupby;
  var groupArr = []
  var filterParams = req.query.filterby;
  var filterValParams = req.query.filterval;
  var whereObject = {merchant_id: merchantId}
  var attributesArr = [[db.Sequelize.fn('COUNT','campaign_id'), "campaigns"]]
  if(filterValParams && filterParams){
    filterValParams = filterValParams.split(',')
    filterParams = filterParams.split(',')
    console.log(filterParams)
    console.log(filterValParams)
    if(filterParams.length != filterValParams.length){
      res.status(422).send({
        message: "Please supply filter val for each filter by param"
      })
      return;
    }
    for(var i = 0; i<filterParams.length; i++){
      whereObject[filterParams[i]] = filterValParams[i] 
     }
  }
  if(groupByParams){
    groupByParams = groupByParams.split(',')
    console.log(groupByParams)
    for(var i =0 ; i<groupByParams.length; i++){
      groupArr.push(groupByParams[i])
      attributesArr.push(groupByParams[i])
    }
  }

  Campaign.findAll({where: whereObject, group:groupArr,raw:true,attributes:attributesArr})
  .then(data => {
    res.send(data)
  })
  .catch(err=>{
    res.status(500).send({
      message:
        err.message || "Error counting campaign"
    });
  })
}

 exports.countStatusTicketPerCampaign = (req,res) => {
    Campaign.findAll({
      attributes:  [
          "campaign_id",
          "campaign_name",
              [
                  // Note the wrapping parentheses in the call below!
                  sequelize.literal(`(
                      SELECT COUNT(*)
                      FROM task_tickets AS tickets
                      WHERE
                          tickets.campaign_id = campaign.campaign_id
                          AND
                          tickets.approval_status = "Pending"
                  )`),
                  'Pending',
                  
              ],
              [
                // Note the wrapping parentheses in the call below!
                sequelize.literal(`(
                    SELECT COUNT(*)
                    FROM task_tickets AS tickets
                    WHERE
                      tickets.campaign_id = campaign.campaign_id
                        AND
                        tickets.approval_status = "APPROVED"
                )`),
                'Approved',
                
            ],[
              // Note the wrapping parentheses in the call below!
              sequelize.literal(`(
                  SELECT COUNT(*)
                  FROM task_tickets AS tickets
                  WHERE
                    tickets.campaign_id = campaign.campaign_id
                      AND
                      tickets.approval_status = "REJECTED"
              )`),
              'Rejected',
          ]
          ]
    , where: {merchant_id : req.body.merchantid}})
    .then(data => {
      res.send(data)
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while counting respondents"
      });
    })
  }