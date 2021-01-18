const { task_question_choices } = require("../models");
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
    var allowedInputValues = ["TEXT", "SINGLE SELECT DROPDOWN", "CHECKBOX", "TRUE OR FALSE", "FLOAT", "IMAGE", "INTEGER", "TIMESTAMP", "CALCULATED VALUE", "DATETIME"]
    // Validate request
    //console.log(req.body)
    if (!req.body.task_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
    if (!req.body.task_questions) {
      res.status(422).send({
        message: "The Task currently has no questions!"
      });
      return;
    }


    task_questions_container = []
    for(i=0;i<req.body.task_questions.length;i++){
      var matchedInput = allowedInputValues.find(element => element == req.body.task_questions[i].required_inputs.toUpperCase())
      if(!matchedInput){
        res.status(422).send({
          message: "One of the required Input not supported"
        });
        return;
      }
      req.body.task_questions[i].required_inputs = req.body.task_questions[i].required_inputs.toUpperCase();
      req.body.task_questions[i].index = i+1;
      task_questions_container.push(req.body.task_questions[i])
    }
    
  
    // Create a task
    const task = {
      task_name: req.body.task_name,
      task_description: req.body.task_description,
      subject_level: req.body.subject_level,
      merchant_id: req.body.merchantid,
      task_classification_id: req.body.task_classification_id,
      task_questions: task_questions_container,
      banner_image: req.body.banner_image
      //task_action_classification_id: "57e2c884-6cfc-4fa2-9cc8-b92f6747f535"
    };

    //console.log(task)
    
    db.sequelize.transaction(transaction =>
      Task.create(task, {include: [
        //TO DO Refactor to handle changes with task questions
        {model:Task_Question, as:"task_questions", include:[{model:Task_Question_Choices}]}
      ],
        transaction
      }).then(data => {
        data.banner_image = "Image Uploaded"
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

  };

// Retrieve all Tasks from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Task.findAll({attributes:{exclude:['banner_image']}, where: condition, include:{model: Task_Classification, where: {task_type: 'Merchandising'}} })
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

// Retrieve all Tasks from the database.
exports.findAllforMerchant = (req, res) => {
  const merchant_id = req.body.merchantid;
  const task_type = req.query.task_type;
  const task_id = req.query.task_id;
  const task_name = req.query.task_name;


  var include_condition = [{model: Task_Classification},{model: Task_Question, include:[{model:Task_Question_Choices}]}]
  var exclude_condition = ['banner_image']
  var where_condition = {
    [Op.or]: [
      { merchant_id: merchant_id },
      { merchant_id: null }
    ]
  }
  if(task_name){
    where_condition.task_name = { [Op.like]: `%${task_name}%` }
  }

  if(task_type){
    include_condition = [
      {model: Task_Classification, where: {task_type: task_type}},
      {model: Task_Question, include:[{model:Task_Question_Choices}]}
    ]
  }
  if(task_id){
    where_condition = {
      task_id : task_id
    }
    exclude_condition =[]
  }

  if((req.query.page)&&(req.query.count_per_page)){
    var page_number = parseInt(req.query.page);
    var count_per_page = parseInt(req.query.count_per_page);  
    var skip_number_of_items = (page_number * count_per_page) - count_per_page;

    Task.findAndCountAll({
      offset:skip_number_of_items, limit: count_per_page,distinct:true,
      attributes:{exclude:exclude_condition}, 
      where: where_condition,
      order:[['createdAt', 'DESC'],
      [{model: Task_Question},'index', 'ASC']], 
      include: include_condition
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
  }
  

  Task.findAll({attributes:{exclude:exclude_condition}, where: where_condition,order:[['createdAt', 'DESC'],
     [{model: Task_Question},'index', 'ASC']], 
  include: include_condition
 })
    .then(data => {
      if(task_id){
        
        res.send(data[0]);
      }
      else{
        res.send(data);
      }  
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
      where: { task_id: id } , 
      include: [{model:Task_Question, as:"task_questions", include:[{model:Task_Question_Choices}]}]
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task was updated successfully."
          });
        } else {
          res.status(422).send({
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

  // Update a Task by the id in the request
exports.chainedUpdate = (req, res) => {
  const id = req.body.task_id;
  const merchant_id = req.body.merchantid;
  var chainedPromises = [];


  db.sequelize.transaction({autocommit:false},transaction => {

  chainedPromises.push(
    Task.update(req.body, {
        where: {
            task_id: id,
            merchant_id: merchant_id
        }, transaction
    }).then(num => {
      if (num != 1) {
        res.status(422).send({
          message: `Cannot update Task with id=${id}. Maybe task does not belong to merchant or was not found.`
        });
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Error updating Task with id=" + id
      });
    })
  );

  //For every question, Push query to update task question
  req.body.task_questions.forEach((element,i) => {
    element.task_id = id;
    element.index = i+1;
    if(element.task_question_id){
      chainedPromises.push(
        Task_Question.update(element, {
            where: {
                task_question_id: element.task_question_id,
            }, transaction
        }).then(num => {
          if (num != 1) {
            res.status(422).send({
              message: `Cannot update Task Question with id=${element.task_question_id}. Maybe task question does not belong to merchant or was not found.`
            });
          }
        })
        .catch(err => {
          res.status(500).send({
            message:
              err.message || "Some error occurred while updating the Task Questions."
          });
        })
      );

    }
    else{
      chainedPromises.push(
        Task_Question.create(element, {transaction}
        ).catch(err => {
          res.status(500).send({
            message:
              err.message || "Some error occurred while creating the Task Questions."
          });
        })
      );
    }
  //If question has choices, Push query to update each choice
    if(element.task_question_choices)
    {
      element.task_question_choices.forEach((choice) => {
        console.log(choice)
        choice.task_question_id = element.task_question_id;
        chainedPromises.push(Task_Question_Choices.upsert(choice, {
          where: {
              choices_id: choice.choices_id,
          }, transaction
      }).catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while updating the Task Question Choices."
        });
      })
        )
      })
      
    }

    });
    return Promise.all(chainedPromises)
    .then(data => {
          res.status(200).send({
            message:
             "Update task successful"
          });
        })
        .catch(err => {
          res.status(500).send({
            message:
              err.message || "Some error occurred while updating the Task Questions."
          });
        });
  })

  



  
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

