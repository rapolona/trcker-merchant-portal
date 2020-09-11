const db = require("../models");
const Task = db.tasks;
const Task_Question = db.task_questions;
const Task_Question_Choices = db.task_question_choices;
const Task_Classification = db.task_classifications;

const Op = db.Sequelize.Op;

// Create and Save a new Task
exports.create = (req, res) => {
    // Validate request
    console.log(req.body)
    if (!req.body.task_action_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a task
    const task = {
      task_name: req.body.task_name,
      task_description: req.body.task_description,
      subject_level: req.body.subject_level,
      merchant_id: req.body.merchantid,
      task_classification_id: req.body.task_classification_id
    };

    console.log(task)
  
    // Save Task in the database
    Task.create(task)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task."
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
    if (!req.body.task_questions) {
      res.status(400).send({
        message: "The Task currently has no questions!"
      });
      return;
    }

    task_questions_container = []
    for(i=0;i<req.body.task_questions.length;i++){
      task_questions_container.push(req.body.task_questions[i])
    }
    
  
    // Create a task
    const task = {
      task_name: req.body.task_name,
      task_description: req.body.task_description,
      subject_level: req.body.subject_level,
      merchant_id: req.body.merchantid,
      task_classification_id: req.body.task_classification_id,
      task_questions: task_questions_container
      //task_action_classification_id: "57e2c884-6cfc-4fa2-9cc8-b92f6747f535"
    };

    console.log(task)
    
    db.sequelize.transaction(transaction =>
      Task.create(task, {include: [
        //TO DO Refactor to handle changes with task questions
        {model:Task_Question, as:"task_questions", include:[{model:Task_Question_Choices}]}
      ],
        transaction
      }).then(data => {
        res.send(data);
      })
      .catch(err => {
        transaction.rollback();
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Task."
        });
      })
    )

    // // Save Task in the database
    // Task.create(task)
    //   .then(data => {
    //     res.send(data);
    //   })
    //   .catch(err => {
    //     res.status(500).send({
    //       message:
    //         err.message || "Some error occurred while creating the Task."
    //     });
    //   });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Task.findAll({ where: condition, include:{model: Task_Classification, where: {task_type: 'Merchandising'}} })
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
  const task_type = req.query.task_type;
  include_condition = {model: Task_Classification}

  if(task_type){
    include_condition = [
      {model: Task_Classification, where: {task_type: task_type}},
      {model: Task_Question, include:[{model:Task_Question_Choices}]}
    ]
  }
  

  Task.findAll({ where: {
    [Op.or]: [
      { merchant_id: merchant_id },
      { merchant_id: null }
    ]
  }, include: include_condition
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

// Find a single Task with an id
exports.findOne = (req, res) => {
    const id = req.body.task_action_id;
  
    Task.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Task with id=" + id
        });
      });
  };

// Update a Task by the id in the request
exports.update = (req, res) => {
    const id = req.body.task_id;
  
    Task.update(req.body, {
      where: { task_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Task with id=${id}. Maybe Task was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Task with id=" + id
        });
      });
  };

// Delete a Task with the specified id in the request
exports.delete = (req, res) => {
    const id = req.body.task_id;
  
    Task.destroy({
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Task with id=${id}. Maybe Task was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Task with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Task.destroy({
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

