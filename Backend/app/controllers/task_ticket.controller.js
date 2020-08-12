const db = require("../models");
const Task_Ticket = db.task_tickets;
const Task_Detail = db.task_details;
const Op = db.Sequelize.Op;

// Update a Task_Ticketn by the id in the request
exports.approve = (req, res) => {
  const id = req.body.task_ticket_id;
  var statusToSet = "APPROVED"
    Task_Ticket.update({approval_status: statusToSet}, {
      where: { task_ticket_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Task Ticket was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Task_Action with id=" + id
        });
      });
  };

  exports.reject = (req, res) => {
    const id = req.body.task_ticket_id;
    var statusToSet = "REJECTED"
      Task_Ticket.update({approval_status: statusToSet}, {
        where: { task_ticket_id: id }
      })
        .then(num => {
          if (num == 1) {
            res.send({
              message: "Task Ticket was updated successfully."
            });
          } else {
            res.send({
              message: `Cannot update Task_Ticket with id=${id}. Maybe Task_Ticket was not found or req.body is empty!`
            });
          }
        })
        .catch(err => {
          res.status(500).send({
            message: "Error updating Task_Action with id=" + id
          });
        });
    };
