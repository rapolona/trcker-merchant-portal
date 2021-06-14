const { campaign_task_associations, tasks } = require("../models");
const db = require("../models");
const User_wallet = db.user_wallets;
const Task_ticket = db.task_tickets;
const Op = db.Sequelize.Op;
const User_Wallet_Audit = db.user_wallet_audit;

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

exports.awardAllTicketsUnderCampaign = (req, res) => {
  const merchant_id = req.body.merchantid
  const campaign_id = req.body.campaign_id
  
  if(!campaign_id){
    res.status(422).send({
      message: `Please specify a campaign id to process`
    });
  }
  
  Task_ticket.findAndCountAll({attributes: ["task_ticket_id", "approval_status", "campaign_id", "task_id", "user_id"], 
  where: {campaign_id: campaign_id, approval_status:"APPROVED", awarded:false}, 
  include: [{model:campaign_task_associations, attributes:["campaign_task_association_id", "campaign_id", "reward_amount", "task_id"]}]})
    .then(data => {
      var taskticketArr = []
      console.log(data.count)
      data.rows.forEach(element => {
        taskticketArr.push(element.get({plain:true}))
      })
      db.sequelize.transaction(transaction => {
        var chainedPromise = []
        taskticketArr.forEach(element => {
          chainedPromise.push(User_wallet.update({current_amount:db.sequelize.literal(`current_amount+${element.campaign_task_associations[0].reward_amount}`)},{where:{user_id : element.user_id}}, transaction))
        });
        return Promise.all(chainedPromise)
        .then(data => {
          Task_ticket.update({awarded: true}, {where: {campaign_id: campaign_id, approval_status:"APPROVED"}})
          .then(awarded => {
            res.send({message: "Succesfully awarded users"})
          })
          .catch(err => {
            console.log(err)
            res.status(500).send({message: "Error awarding approved tickets"})
          })
        })
        .catch(err => {
          console.log(err)
          res.status(500).send({message: "Error updating user wallet"})
        })
      })
    })
}

exports.editWallet = (req, res) =>{
  const user_id = req.body.user_id
  const amount = req.body.amount
  const addOrRemoveFlag = req.body.operation
  var updatedAtTime = new Date()
  updatedAtTime = updatedAtTime.setTime(updatedAtTime.getTime() - (updatedAtTime.getTimezoneOffset()*60000))
  var addOrRemoveString = ""

  const user_wallet_audit_obj = {
    amount_changed_to: amount,
    last_updated_by: req.body.adminid,
    last_updated_by_type: "ADMIN"
  }

  switch(addOrRemoveFlag){
    case "add":
      addOrRemoveString = "+";
      break;
    case "subtract":
      addOrRemoveString = "-";
      break;
    default:
      addOrRemoveString = ""
  }

  User_wallet.update({current_amount:db.sequelize.literal(`current_amount${addOrRemoveString}${amount}`), updatedAt: updatedAtTime}, {where:{user_id : user_id}})
  .then(num => {
    if(num == 1 ){
      User_wallet.findOne({where:{user_id:user_id}})
      .then(data => {
        if(data){
          user_wallet_audit_obj.amount_changed_to = data.current_amount
          User_Wallet_Audit.create(user_wallet_audit_obj)
      .then(data  => {
        if(data){
          res.send({message: "Updated wallet amount succesfully"})
        }
        else{
          res.status(500).send({message: "No audit created"})
        }
      })
      .catch(err => {
        console.log(err)
        res.status(500).send({
          message: "Error creating audit"
        })
      })
        }
      })
    }
  })
  .catch(err => {
    console.log(err)
    res.status(500).send({
      message: "Error updating wallet"
    })
  })
}