const db = require("../models");
const moment = require("moment");
const { branches } = require("../models");
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
      res.status(400).send({
        message: "Cannot create campaign whose end date occurs before the start date"
      });
      return;
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
  const branches_container = []
  
  for(i=0;i<req.body.branches.length;i++){
    branches_container.push(req.body.branches[i])
  }
  console.log(req.body.tasks)
  const campaign = {
      merchant_id: req.body.merchantid,
      start_date: req.body.start_date,
      end_date: req.body.end_date,
      budget: req.body.budget,
      campaign_name: req.body.campaign_name,
      campaign_description: req.body.campaign_description,
      audience_age_min: req.body.audience_age_min,
      audience_age_max: req.body.audience_age_max,
      audience_gender: req.body.audience_gender,
      allowed_account_level: req.body.allowed_account_level,
      super_shoppers: req.body.super_shoppers,
      allow_everyone: req.body.allow_everyone,
      status: req.body.status,
      campaign_type: req.body.campaign_type,
      campaign_task_associations: req.body.tasks,
      campaign_branch_associations: branches_container,
      campaign_reward: req.body.reward
  };
  if(campaign.allow_everyone){
    campaign.allowed_account_level='any'
  }

  console.log(campaign)

  //Save Campaign in the database
//   Campaign.create(campaign, {include: [
//     {model:Task_Questions, as:"task_questions", include:[{model:Task_Questions}]},
//     {model:Campaign_Branch_Association, as:"campaign_branch_associations"},
//     {model:Campaign_Reward, as:"campaign_reward"}
//   ]})
//     .then(data => {
//       res.send(data);
//     })
//     .catch(err => {
//       res.status(500).send({
//         message:
//           err.message || "Some error occurred while creating the Campaign."
//       });
//     });
// };

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
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Campaign.findAll({ where: null , include: [Branch]})
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
    const id = req.params.id;
  
    Campaign.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Campaign with id=" + id
        });
      });
  };

// Update a Campaign by the id in the request
exports.update = (req, res) => {
    const id = req.body.campaign_id;
  
    Campaign.update(req.body, {
      where: { campaign_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Campaign with id=${id}. Maybe Campaign was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Campaign with id=" + id
        });
      });
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
        res.send({
          message: `Cannot delete Campaign with id=${id}. Maybe Campaign was not found!`
        });
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Could not delete Campaign with id=" + id
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
    Task_Ticket.findAll({include:[{model:Campaign, where:{merchant_id: req.body.merchantid}, attributes:[]}],group:['campaign_id'], attributes:[
      [db.Sequelize.fn("COUNT", "user_id"), "respondents"], 'campaign_id'
    ]})
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