const { campaign_task_associations, tasks } = require("../models");
const db = require("../models");
const User_wallet = db.user_wallets;
const Task_ticket = db.task_tickets;
const Op = db.Sequelize.Op;

exports.awardTicketAmount = (req, res) => {
    const merchant_id = req.body.merchantid;
    const task_ticket_id = req.body.task_ticket_id;

  
    Task_ticket.findOne({where: {task_ticket_id: task_ticket_id}, include: {model: tasks} })
      .then(data => {
        
        if(data.awarded==true){
            res.status(422).send({
                message: `Error sending money to user. Ticket has already been awarded.`
              });
        }

        campaign_task_associations.findOne({where: {task_id: data.task_id, campaign_id: data.campaign_id} })
        .then(new_data => {
            var new_wallet_value = 'current_amount + '+ new_data.reward_amount
            console.log(new_wallet_value)
            User_wallet.update({current_amount: db.Sequelize.literal(new_wallet_value) }, {
                where: { user_id: data.user_id }
              })
                .then(num => {
                  if (num == 1) {
                    Task_ticket.update({awarded: true }, {
                        where: { task_ticket_id: task_ticket_id }
                      })
                      .then(num => {
                        if (num == 1) {
                            res.send({
                              message: "User wallet was updated successfully."
                            });                        
      
                        } else {
                          res.status(422).send({
                            message: `Error sending money to user`
                          });
                        }
                      })
                      .catch(err => {
                        res.status(500).send({
                          message: "Error sending money to user"
                        });
                      });

                  } else {
                    res.status(422).send({
                      message: `Error sending money to user`
                    });
                  }
                })
                .catch(err => {
                  res.status(500).send({
                    message: "Error sending money to user"
                  });
                });
       
        })
        .catch(err => {
          res.status(500).send({
            message: "Error retrieving Task Ticket with id=" + task_ticket_id + err
          });
        });
        
        
        
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Task Ticket with id=" + task_ticket_id + err
        });
      });
  };
