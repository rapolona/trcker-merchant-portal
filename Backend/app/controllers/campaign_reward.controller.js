const db = require("../models");
const Campaign_Reward = db.campaign_rewards;
const Op = db.Sequelize.Op;

// Create and Save a new Campaign_Reward
exports.create = (req, res) => {
    // Validate request
    if (!req.body.product_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a campaign_reward
    const campaign_reward = {
      reward_name: req.body.reward_name,
      reward_description: req.body.reward_description,
      type: req.body.type,
      amount: req.body.amount,
      campaign_id: req.body.campaign_id
    };

    console.log(campaign_reward)
  
    // Save Campaign_Reward in the database
    Campaign_Reward.create(campaign_reward)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Campaign_Reward."
        });
      });
  };

// Retrieve all Products from the database.
exports.findAll = (req, res) => {

  const id = req.body.merchantid;
  var condition = id ? { merchant_id: { [Op.eq]: `${id}` } } : null;

  Campaign_Reward.findAll({ where: condition })
    .then(data => {
      res.send(data);
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving campaign_rewards."
      });
    });
};

// Find a single Campaign_Reward with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Campaign_Reward.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Campaign_Reward with id=" + id
        });
      });
  };

// Update a Campaign_Reward by the id in the request
exports.update = (req, res) => {
    const id = req.body.campaign_reward_id;
  
    Campaign_Reward.update(req.body, {
      where: { campaign_reward_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Reward was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Campaign_Reward with id=${id}. Maybe Campaign_Reward was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Campaign_Reward with id=" + id
        });
      });
  };

// Delete a Campaign_Reward with the specified id in the request
exports.delete = (req, res) => {
    const campaign_reward_id = req.body.campaign_reward_id;
    
  
    Campaign_Reward.destroy({
      where: { campaign_reward_id: campaign_reward_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Reward was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Campaign_Reward with id=${id}. Maybe Campaign_Reward was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Campaign_Reward with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Campaign_Reward.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Merchants were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Merchants."
        });
      });
  };

