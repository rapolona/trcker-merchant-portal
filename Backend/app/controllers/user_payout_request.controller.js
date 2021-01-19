const db = require("../models");
const User_payout_request = db.userpayoutrequests;
const Op = db.Sequelize.Op;


// Retrieve all Payout requests from the database.
exports.findAll = (req, res) => {
    var page_number = 1;
    var count_per_page = 25;
    if((req.query.page)&&(req.query.count_per_page)){
        var page_number = parseInt(req.query.page);
        var count_per_page = parseInt(req.query.count_per_page);
        
      }
    var skip_number_of_items = (page_number * count_per_page) - count_per_page;

    User_payout_request.findAndCountAll({offset:skip_number_of_items, limit: count_per_page})
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






