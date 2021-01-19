const db = require("../models");
const User_payout_request = db.userpayoutrequests;
const Op = db.Sequelize.Op;


// Retrieve all Payout requests from the database.
exports.findAll = (req, res) => {



  User_payout_request.findAll()
    .then(data => {
      res.send(data);
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving products."
      });
    });
};






