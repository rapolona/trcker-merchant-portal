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
    
    if(req.body.merchantid){
      
      condition.merchant_id = id;
    }
  
    Campaign.findAll({ where: condition , include: [Branch], order:[["createdAt", "DESC"]]})
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
    attributes: { exclude: ['at_home_campaign','total_reward_amount','createdAt','updatedAt','merchant_id','campaign_id']}
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
    console.log(req.body.tasks)
    db.sequelize.transaction({autocommit:false},transaction => {
      return Promise.all([
        Campaign.update(req.body, {
          where: { campaign_id: id, merchant_id : req.body.merchantid, status:0},
          transaction: transaction
        }),
        Campaign_Task_Association.destroy({
          where: {campaign_id: id},
          transaction:transaction}),
        Campaign_Task_Association.bulkCreate(req.body.tasks, {transaction:transaction}),
        Campaign_Reward.update(req.body.reward, {where: {campaign_id : id}, transaction:transaction}),
        Campaign_Branch_Association.destroy({
          where: {campaign_id: id},
          transaction:transaction
        }),
        Campaign_Branch_Association.bulkCreate(req.body.branches, {transaction:transaction})
      ])
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
        message: err || "Error updating campaign"
      })
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

// Delete all Campaigns from the database.
exports.deleteAll = (req, res) => {
    Campaign.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Campaigns were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Campaigns."
        });
      });
  };


  exports.countRespondents = (req,res) => {
    var groupByParam = req.query.groupby
    var groupArr = ['campaign_id']
    var includeArr = [{model:Campaign, where:{merchant_id: req.body.merchantid}, attributes:['campaign_name', 'campaign_description']}]
    if(groupByParam && groupByParam == "BRANCH"){
      groupArr.push('task_ticket.branch_id')
      includeArr.push({model:Branch, attributes:['name']})
    }
    Task_Ticket.findAll({include:includeArr,group:groupArr, attributes:[
      [db.Sequelize.fn("COUNT", "user_id"), "respondents"], 'campaign_id']})
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
  
    Campaign.findAll({where: {merchant_id: merchantId, status: 1}, raw:true,attributes:[[db.Sequelize.fn('COUNT','campaign_id'), "active_campaigns"]]})
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