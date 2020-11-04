const db = require("../models");
const Task_Classification = db.task_classifications;
const Op = db.Sequelize.Op;

// Create and Save a new Task_Classification
exports.create = (req, res) => {
    // Validate request
    if (!req.body.name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a task_classification
    const task_classification = {
      name: req.body.name,
      description: req.body.description,
      task_type: req.body.task_type
    };

    console.log(task_classification)
  
    // Save Task_Classification in the database
    Task_Classification.create(task_classification)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task_Classification."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const task_type = req.query.task_type;
    var condition = task_type ? { task_type: { [Op.like]: `%${task_type}%` } } : null;
  
    Task_Classification.findAll({ where: condition })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task_action_classifications."
        });
      });
  };

// Find a single Task_Classification with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Task_Classification.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Task_Classification with id=" + id
        });
      });
  };

// Update a Task_Classification by the id in the request
exports.update = (req, res) => {
    const id = req.body.task_action_classification_id;
  
    Task_Classification.update(req.body, {
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Classification was updated successfully."
          });
        } else {
          res.status(422).send({
            message: `Cannot update Task_Classification with id=${id}. Maybe Task_Classification was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Task_Classification with id=" + id
        });
      });
  };

// Delete a Task_Classification with the specified id in the request
exports.delete = (req, res) => {
    const id = req.params.id;
  
    Task_Classification.destroy({
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Classification was deleted successfully!"
          });
        } else {
          res.status(422).send({
            message: `Cannot delete Task_Classification with id=${id}. Maybe Task_Classification was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Task_Classification with id=" + id
        });
      });
  };

// Delete all Task_Classification from the database.
exports.deleteAll = (req, res) => {
    Task_Classification.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Task_Classification were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Task_Classification."
        });
      });
  };

