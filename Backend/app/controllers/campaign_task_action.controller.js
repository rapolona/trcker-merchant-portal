const db = require("../models");
const Campaign_Task_Action = db.campaign_task_actions;
const Op = db.Sequelize.Op;

// Create and Save a new Campaign_Task_Action
exports.create = (req, res) => {
    // Validate request
    if (!req.body.title) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a campaign_task_action
    const campaign_task_action = {
      title: req.body.title,
      description: req.body.description,
      required_inputs: req.body.required_inputs,
      benefits: req.body.benefits,
      campaign_id: req.body.campaign_id,
      task_action_id: req.body.task_action_id
    };

    console.log(campaign_task_action)
  
    // Save Campaign_Task_Action in the database
    Campaign_Task_Action.create(campaign_task_action)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Campaign_Task_Action."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Campaign_Task_Action.findAll({ where: condition })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving campaign_task_actions."
        });
      });
  };

// Find a single Campaign_Task_Action with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Campaign_Task_Action.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Campaign_Task_Action with id=" + id
        });
      });
  };

// Update a Campaign_Task_Action by the id in the request
exports.update = (req, res) => {
    const task_id = req.body.campaign_task_action_id;
  
    Campaign_Task_Action.update(req.body, {
      where: { campaign_task_action_id: task_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Task_Action was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Campaign_Task_Action with id=${id}. Maybe Campaign_Task_Action was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Campaign_Task_Action with id=" + id
        });
      });
  };

// Delete a Campaign_Task_Action with the specified id in the request
exports.delete = (req, res) => {
    const task_id = req.body.campaign_task_action_id;
  
    Campaign_Task_Action.destroy({
      where: { campaign_task_action_id: task_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Task_Action was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Campaign_Task_Action with id=${id}. Maybe Campaign_Task_Action was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Campaign_Task_Action with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Campaign_Task_Action.destroy({
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

