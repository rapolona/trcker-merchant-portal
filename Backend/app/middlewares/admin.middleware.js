const db = require("../models");
const AdminSession  = db.adminsessions
const jwt = require("jsonwebtoken");
module.exports = {
    isAuthenticated: (req, res, next) => {
    if(req.headers.authorization){
      var token = req.headers.authorization.split(' ')[1]
      try{
        var tokenData = jwt.verify(token, "TrckerTestSecret")
        console.log(tokenData)
        AdminSession.findOne({where: {"admin_id" : tokenData.adminid, token: token}})
        .then(data => {
          if(data){
            req.body.adminid = tokenData.adminid
            req.body.merchantid = tokenData.merchantid
            next();
          }
          else{
            res.status(403).send({
              message: "Unauthenticated Request"
            })
          }
        })
        .catch(err => {
          res.status(500).send({
            message: err||"Internal error"
          })
        })
      }
      catch(err){
        if(err.name == "TokenExpiredError"){
          res.status(401).send({
            message: err||"Internal error"
          })
        }
        else{
          res.status(500).send({
            message: err||"Internal error"
          }) 
        }
      }
    }
    else if (req.body.passwordToken){
      next();
    }
    else{
      res.status(403).send({
        message: "Unauthenticated Request"
      })
    }
  }
};