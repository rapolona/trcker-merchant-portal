const db = require("../models");
const Task_Ticket = db.task_tickets;
const Task_Detail = db.task_details;
const User_Detail = db.userdetails;
const Task_Question = db.task_questions;
const Op = db.Sequelize.Op;

// Update a Task_Ticketn by the id in the request
exports.approve = (req, res) => {
  const id = req.body.task_ticket_id;
  var statusToSet = "APPROVED"
  Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: "Pending"}})
  .then(data => {
    if(data){
      Task_Ticket.update({approval_status: statusToSet}, {
        where: { task_ticket_id: id }
      })
        .then(num => {
          if (num == 1) {
            res.send({
              message: "Task Ticket was updated successfully."
            });
          } else {
            res.status(422).send({
              message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
            });
          }
        })
        .catch(err => {
          res.status(500).send({
            message: "Error updating Task Ticket with id=" + id
          });
        });
    }
    else{
      res.status(422).send({
        message: "Cannot update the ticket repeatedly"
      })
    }
  })
  .catch(err => {
    res.status(500).send({
      message: "Something went wrong updating the ticket"
    })
  })
  };

  exports.reject = (req, res) => {
    const id = req.body.task_ticket_id;
    var statusToSet = "REJECTED"
    Task_Ticket.findOne({where: {task_ticket_id: id, approval_status: "Pending"}})
    .then(data => {
      if(data){
        Task_Ticket.update({approval_status: statusToSet}, {
          where: { task_ticket_id: id }
        })
          .then(num => {
            if (num == 1) {
              res.send({
                message: "Task Ticket was updated successfully."
              });
            } else {
              res.status(422).send({
                message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
              });
            }
          })
          .catch(err => {
            res.status(500).send({
              message: "Error updating Task Ticket with id=" + id
            });
          });
      }
      else{
        res.status(422).send({
          message: "Cannot update the ticket repeatedly"
        })
      }
    })
    .catch(err => {
      res.status(500).send({
        message: "Something went wrong updating the ticket"
      })
    })
    };


    exports.findTicketsByUser = (req, res) => {      
      const campaign_id  = req.body.campaign_id;
      Task_Ticket.findAll({
        where: {campaign_id: campaign_id},
        include: [
          {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email']}
        ],
        attributes: ["user_id"],
        group: ["user_id"]       
      })
      .then(data => {
        res.send(data)
      })
      .catch(err => {
        res.status(500).send({
          message: err || "Error retrieving users with tickets"
        })
      })
    }

    exports.findTicketsOfUser = (req, res) => {
      const id = req.body.campaign_id
      const userid = req.body.user_id
       //TO DO: Refactor
      Task_Ticket.findAll({
        where: {campaign_id : id, user_id: userid}, 
        include: [
          {model: Task_Detail, include: [
            {model:Task_Question, attributes: ['question']}]
          },
          {model: User_Detail, as:'user_detail', attributes: ['first_name', 'last_name', 'account_level', 'email']}
        ]
        })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        console.log(err)
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving task tickets."
        });
      });
    }