const db = require("../models");
const moment = require("moment");
const { branches, tasks, campaign_branch_associations, campaign_task_associations } = require("../models");
const Campaign = db.campaigns;
const Branch = db.branches;
const City = db.cities;
const Task_Questions = db.task_questions;
const Task_Question_Choices = db.task_question_choices;
const Campaign_Branch_Association = db.campaign_branch_associations;
const Campaign_Task_Association = db.campaign_task_associations;
const Campaign_City_Association = db.campaign_city_associations;
const Campaign_Reward = db.campaign_rewards;
const Task_Ticket = db.task_tickets;
const Op = db.Sequelize.Op;
const sequelize = db.sequelize;
const s3Util = require("../utils/s3.utils.js");





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
      if(req.body.permanent_campaign=1){at_home_respondent_count=0;}
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

  // Create a campaign
  
  for(i=0;i<req.body.tasks.length; i++){
    req.body.tasks[i].index = i+1;
  }
  for(i=0;i<branches_container.length;i++){
    branches_container[i]["submitted_response_count"]=0;
    branches_container[i]["status"]=0
    if(req.body.permanent_campaign==1){branches_container[i]["respondent_count"]=0;}
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

  //Set age filters if null
  if(req.body.audience_age_min==null){ req.body.audience_age_min = 0};
  if(req.body.audience_age_max==null){ req.body.audience_age_max = 999};

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
      audience_city: req.body.audience_city,
      allowed_account_level: req.body.allowed_account_level,
      super_shoppers: req.body.super_shoppers,
      allow_everyone: req.body.allow_everyone,
      status: campaign_status,
      at_home_campaign: at_home_campaign,
      permanent_campaign: req.body.permanent_campaign,
      campaign_type: req.body.campaign_type,
      campaign_task_associations: req.body.tasks,
      campaign_branch_associations: branches_container
  };
  //Setting city associations
  if(req.body.audience_cities){
    campaign.campaign_city_associations= req.body.audience_cities;
  }else{
    campaign.campaign_city_associations = {city_id:9999}
  }

  if(campaign.allow_everyone){
    campaign.allowed_account_level='any'
  }

  console.log(campaign)



  var chainedPromises = [];
  db.sequelize.transaction(transaction =>
    Campaign.create(campaign, {include: [
      {model:Campaign_Task_Association, as:"campaign_task_associations"},
      {model:Campaign_Branch_Association, as:"campaign_branch_associations"},
      {model:Campaign_City_Association, as:"campaign_city_associations"}
    ],
      transaction
    }).then(data => {
      if(req.body.thumbnail_image_name && req.body.thumbnail_image_base64){
        const now = moment().format('XX')
        var thumbnail_file_name = "Thumbnail_"+data.campaign_id+"_"+ now+"_"+req.body.thumbnail_image_name
        chainedPromises.push(
          s3Util.s3Upload(req.body.thumbnail_image_base64, "ThumbnailImages"+"/" + thumbnail_file_name, "trcker-campaign-images",{})
          .catch(err=>{
            transaction.rollback()
            console.log("Error uploading to S3" + " "+ err.message)
            res.status(500).send({
              message: err.code || "Error uploading image to s3"
            })
          }))
        console.log('editing campaign id '+ data.campaign_id)
          chainedPromises.push(
            Campaign.update({thumbnail_url: thumbnail_file_name}, {
              where: { campaign_id: data.campaign_id }, transaction
            })
              .then(num => {
                if (num == 1) {
                  //res.send(data);
                } else {
                  res.status(422).send({
                    message: `Cannot update Campaign with id=${data.campaign_id}. Maybe Campaign was not found or req.body is empty!`
                  });
                }
              })
              .catch(err => {
                res.status(500).send({
                  message: err.code+" Error updating Campaign with id=" + data.campaign_id
                });
              })
    
          )
          return Promise.all(chainedPromises)
          .then(newdata=> {
            res.send(data)
          })
          .catch(err => {
            console.log("Error creating campaign")
          })
      } else{
        res.send(data);
      }
      

  
      
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
    var condition = {
      merchant_id:id
    }
    

    //Check for filters
    if(req.query.campaign_name){
      condition.campaign_name = { [Op.like]: `%${req.query.campaign_name}%` } ; //Searching by campaign name
    }
    if(req.query.status){
      condition.status = { [Op.like]: `%${req.query.status}%` }
    }
    //TO:DO Filters for date
    if(req.query.date_range_start && req.query.date_range_end){
      condition.end_date = {[Op.gte]: req.query.date_range_start,[Op.lte]: req.query.date_range_end+' 23:59:00.000Z'};
    } 
    else {
      if(req.query.date_range_start){
        condition.end_date= {[Op.gte]: req.query.date_range_start};
      }
      if(req.query.date_range_end){
        condition.end_date= {[Op.lte]: req.query.date_range_end+' 23:59:00.000Z'};
      }
    }
    console.log(condition)

 
    var page_number = 1;
    var count_per_page = null;

    if((req.query.page)&&(req.query.count_per_page)){
      var page_number = parseInt(req.query.page);
      var count_per_page = parseInt(req.query.count_per_page);
      var skip_number_of_items = (page_number * count_per_page) - count_per_page
    }

    Campaign.findAndCountAll({ offset:skip_number_of_items, limit: count_per_page ,attributes:{exclude:['thumbnail_url']},where:condition , order:[["createdAt", "DESC"]]})
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
    




  

  };

// Find a single Campaign with an id
exports.findOne = (req, res) => {
  const campaign_id = req.params.campaign_id;
  const merchant_id = req.body.merchantid;
  const duplicate = req.query.duplicate;
  var condition = { 
    where: 
    {
      merchant_id: merchant_id, 
      campaign_id: campaign_id
    } , 
    include: [
      {model:Branch, attributes:['branch_id','name'], through: {attributes: ['campaign_branch_association_id','respondent_count']} },
      {model:tasks, attributes:['task_id','task_name'], through: {attributes: ['campaign_task_association_id','reward_amount', 'mandatory']}},
      {model:City, attributes:['Id']}

    ],
    attributes: { exclude: ['total_reward_amount','createdAt','updatedAt','merchant_id','campaign_id']}
  };

    Campaign.findOne(condition)
      .then(data => {
        
        new_result = data.get({plain:true});
        for (i = 0; i < new_result.branches.length; i++){
          new_result.branches[i].respondent_count = new_result.branches[i].campaign_branch_association.respondent_count;
          if(duplicate==0){new_result.branches[i].campaign_branch_association_id = new_result.branches[i].campaign_branch_association.campaign_branch_association_id}
          delete new_result.branches[i].campaign_branch_association;
        }
        for (i = 0; i < new_result.tasks.length; i++){
          new_result.tasks[i].reward_amount = new_result.tasks[i].campaign_task_association.reward_amount;
          new_result.tasks[i].mandatory = new_result.tasks[i].campaign_task_association.mandatory;
          if(duplicate==0){new_result.tasks[i].campaign_task_association_id = new_result.tasks[i].campaign_task_association.campaign_task_association_id}
          delete new_result.tasks[i].campaign_task_association;
        }
        for (i = 0; i < new_result.cities.length; i++){
          new_result.cities[i].city_id = new_result.cities[i].Id
          if(duplicate==0){new_result.cities[i].campaign_city_association_id = new_result.cities[i].campaign_city_association.campaign_city_association_id}
          delete new_result.cities[i].campaign_city_association;
          delete new_result.cities[i].Id
        }
        
        new_result.audience_cities = new_result.cities
        delete new_result.cities;
        new_result.start_date = new_result.start_date.toISOString().substring(0,10);
        new_result.end_date = new_result.end_date.toISOString().substring(0,10);
        console.log(data.thumbnail_url)
        if(data.thumbnail_url){
          s3Util.s3getHeadObject("trcker-campaign-images", "ThumbnailImages/"+data.thumbnail_url)
          .then(new_data => {
            console.log(new_data)
            var signedThumbnailImageURL = s3Util.s3GetSignedURL("trcker-campaign-images", "ThumbnailImages/"+data.thumbnail_url)

            new_result.signed_thumbnail_url = signedThumbnailImageURL
            console.log(new_result)
            res.send(new_result)
          })
          .catch(err => {
            res.status(500).send({
              message: err.code 
            });
          })
       } else {
        res.send(new_result);
       }



        

      })
      .catch(err => {
        res.status(500).send(err);
      });
  };

// Update a Campaign by the id in the request
exports.update = (req, res) => {
    const id = req.body.campaign_id;
    var at_home_flag = null
    if(req.body.tasks){
      for(var i = 0; i < req.body.tasks.length; i++){
        req.body.tasks[i].campaign_id = id
        req.body.tasks[i].index = i+1;
      }
      for(i=0;i<req.body.tasks.length;i++){
        total_reward_amount = total_reward_amount + parseFloat(req.body.tasks[i].reward_amount)
      }
    }
    if(req.body.branches){
      at_home_flag = false;
      for(var i=0; i<req.body.branches.length;i++){
        req.body.branches[i].campaign_id = id
        req.body.branches[i].submitted_response_count = 0;
        req.body.branches[i].status = 0;
      }
    }
    if(req.body.audience_cities){
      for(var i=0; i<req.body.audience_cities.length;i++){
        req.body.audience_cities[i].campaign_id = id
      }
    }

    if(req.body.at_home_campaign){
      req.body.branches = [];
      at_home_flag = true
      var at_home_respondent_count=req.body.at_home_respondent_count;
      var at_home_branch_id = "fbe9b0cf-5a77-4453-a127-9a8567ff3aa7";
      req.body.branches.push({"campaign_id":id ,"branch_id":at_home_branch_id, "respondent_count":at_home_respondent_count});
    }
    var total_reward_amount = 0;

    

    if(req.body.end_date){
      req.body.end_date = req.body.end_date + ' 23:59:00.000Z'
    }
    var campaignBody = {
      start_date: req.body.start_date,
      end_date: req.body.end_date,
      budget: req.body.budget,                                                                        
      total_reward_amount: total_reward_amount,
      status: req.body.status,
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
      at_home_campaign: at_home_flag,
      at_home_respondent_count: req.body.at_home_respondent_count,
      campaign_type: req.body.campaign_type,
    }


    
    Campaign.update(campaignBody, {where: { campaign_id: id, merchant_id : req.body.merchantid },})
    .then(num => {
      if(num == 1){
        db.sequelize.transaction({autocommit:false},transaction => {
          var campaignUpdateTransactions = []
          //If merchant wants to update tasks, push task destroy & create promises
          if(req.body.tasks){
            req.body.tasks.forEach(element => {
              if(element.campaign_task_association_id){
                campaignUpdateTransactions.push(
                  Campaign_Task_Association.update(element, {where: {campaign_id: id, campaign_task_association_id: element.campaign_task_association_id}, transaction})
                  .catch(err => {
                    console.log(`Error updating Campaign Task Association with id = ${element.campaign_task_association_id}`)
                    console.log(err)
                  })
                )
              }
              else{
                campaignUpdateTransactions.push(
                  Campaign_Task_Association.create(element, {transaction:transaction})
                  .catch(err => {
                    console.log("Error creating new Campaign Task Association")
                    console.log(err)
                  })
                )
              }
            });
          }
          //If merchant wants to update branches, push branch destroy & create promises
          if(req.body.branches || req.body.at_home_campaign){
            req.body.branches.forEach(element => {
              if(element.campaign_branch_association_id){
                campaignUpdateTransactions.push(Campaign_Branch_Association.update(element, {where: {campaign_id: id,campaign_branch_association_id: element.campaign_branch_association_id},transaction:transaction})
                .catch(err => {
                  console.log(`Error updating Campaign Branch Association with id = ${element.campaign_branch_association_id}`)
                  console.log(err)
                })
                )
              }
              else{
                campaignUpdateTransactions.push(Campaign_Branch_Association.create(element, {transaction:transaction})
                .catch(err => {
                  console.log("Error creating new Campaign Branch Association")
                  console.log(err)
                })
                )
              }
            });
          }
          //If merchant wants to update audience_cities, cities task destroy & create promises
          if(req.body.audience_cities){
            req.body.audience_cities.forEach(element => {
              if(element.campaign_city_association_id){
                campaignUpdateTransactions.push(Campaign_City_Association.update(element,  {where: {campaign_id:id,campaign_city_association_id: element.campaign_city_association_id},transaction:transaction})
                .catch(err => {
                  console.log(`Error updating Campaign City Association with id = ${element.campaign_city_association_id}`)
                  console.log(err)
                })
                )
              }
              else{
                campaignUpdateTransactions.push(Campaign_City_Association.create(element, {transaction:transaction})
                .catch(err => {
                  console.log("Error creating new Campaign City Association")
                  console.log(err)
                }))
              }
            })
          }

          return Promise.all(campaignUpdateTransactions)
        })
        .then(data => {
          if(data){
            res.send({
              message: "Campaign updated succesfully"
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
        res.status(422).send({message:"Error updating campaign"})
        return;
      }
    })  
  };

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