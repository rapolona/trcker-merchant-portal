const db = require("../models");
const AdminSession = db.adminsessions;

exports.destroySession = (req,res) => {
    var token = req.headers.authorization.split(' ')[1]
    AdminSession.destroy({where: {admin_id: req.body.adminid, token:token}})
    .then(num => {
        if (num == 1) {
            res.send({
            message: "Admin Session was deleted successfully!"
            });
        } else {
            res.status(422).send({
            message: `Cannot delete Admin Session with id=${id}. Maybe Admin was not found!`
            });
        }
    })
    .catch(err => {
        res.status(500).send({
            message:
                err.message || "Some error occured while destroying Admins session."
        });
    });
};