const db = require("../models");
const AdminDetail = db.admindetails;

exports.findOne = (req,res) => {
    const id = req.body.adminid;

    AdminDetail.findOne({where: {"admin_id": id}})
        .then(data => {
            res.send(data);
        })
        .catch(err => {
            res.status(500).send({
                message:
                    err.message || "Error retrieving UserDetail with id="+id
            });
        });
};