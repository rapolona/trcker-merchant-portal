const db = require("../models");
const Task_Action = db.task_actions;
const Task_Action_Classification = db.task_action_classifications;
const Op = db.Sequelize.Op;

// Create and Save a new Task_Action
exports.create = (req, res) => {
    // Validate request
    console.log(req.body)
    if (!req.body.task_action_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a task_action
    const task_action = {
      task_action_name: req.body.task_action_name,
      task_action_description: req.body.task_action_description,
      subject_level: req.body.subject_level,
      merchant_id: req.body.merchantid,
      task_action_classification_id: req.body.task_action_classification_id
    };

    console.log(task_action)
  
    // Save Task_Action in the database
    Task_Action.create(task_action)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task_Action."
        });
      });
  };

exports.createCustom = (req, res) => {
    // Validate request
    console.log(req.body)
    if (!req.body.task_action_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a task_action
    const task_action = {
      task_action_name: req.body.task_action_name,
      task_action_description: req.body.task_action_description,
      subject_level: req.body.subject_level,
      merchant_id: req.body.merchantid,
      task_action_classification_id: "57e2c884-6cfc-4fa2-9cc8-b92f6747f535"
    };

    console.log(task_action)
  
    // Save Task_Action in the database
    Task_Action.create(task_action)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task_Action."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Task_Action.findAll({ where: condition, include: Task_Action_Classification })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task_actions."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAllforMerchant = (req, res) => {
  const merchant_id = req.body.merchantid;
  

  Task_Action.findAll({ where: {
    [Op.or]: [
      { merchant_id: merchant_id },
      { merchant_id: null }
    ]
  }, include: Task_Action_Classification
 })
    .then(data => {
      res.send(data);
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving task_actions."
      });
    });
};

// Find a single Task_Action with an id
exports.findOne = (req, res) => {
    const id = req.body.task_action_id;
  
    Task_Action.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Task_Action with id=" + id
        });
      });
  };

// Update a Task_Action by the id in the request
exports.update = (req, res) => {
    const id = req.body.task_action_id;
  
    Task_Action.update(req.body, {
      where: { task_action_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Action was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Task_Action with id=${id}. Maybe Task_Action was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Task_Action with id=" + id
        });
      });
  };

// Delete a Task_Action with the specified id in the request
exports.delete = (req, res) => {
    const id = req.body.task_action_id;
  
    Task_Action.destroy({
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Action was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Task_Action with id=${id}. Maybe Task_Action was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Task_Action with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Task_Action.destroy({
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

