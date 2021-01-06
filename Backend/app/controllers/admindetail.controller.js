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

exports.update = (req, res) => {
    const id = req.body.adminid;

   AdminDetail.update(req.body, {where: {"admin_id": id}})
   .then(num => {
       if(num == 1){
        res.send({
            message: "Succesfully updated admin details"
        })
       }
       else{
        res.status(422).send({
            message: "Could not update admin detail of id sepcified"
        })
       }
   })
   .catch(err => {
       res.status(500).send({
           message: err.message || "Error updating admin details"
       })
   })

    
}