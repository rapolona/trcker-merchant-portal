const db = require("../models");
const Task_Question = db.task_questions;
const Op = db.Sequelize.Op;

// Create and Save a new Task_Question
exports.create = (req, res) => {
    // Create a task_question
    const task_question = {
      question: req.body.question,
      required_inputs: req.body.required_inputs,
      task_id: req.body.task_id,
      task_question_choices: req.body.task_question_choices
    };

    console.log(task_question)
  
    // Save Task_Question in the database
    Task_Question.create(task_question, {include: [{model: Task_Question_Choices, as:"task_question_choices"}]})
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task_Question."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Task_Question.findAll({ where: condition })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task_questions."
        });
      });
  };

// Find a single Task_Question with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Task_Question.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Task_Question with id=" + id
        });
      });
  };

// Update a Task_Question by the id in the request
exports.update = (req, res) => {
    const task_id = req.body.task_question_id;
  
    Task_Question.update(req.body, {
      where: { task_question_id: task_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Question was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Task_Question with id=${id}. Maybe Task_Question was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Task_Question with id=" + id
        });
      });
  };

// Delete a Task_Question with the specified id in the request
exports.delete = (req, res) => {
    const task_id = req.body.task_question_id;
  
    Task_Question.destroy({
      where: { task_question_id: task_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task_Question was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Task_Question with id=${id}. Maybe Task_Question was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Task_Question with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Task_Question.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Task_Question were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Task_Question."
        });
      });
  };

