const { sequelize } = require("../models");
const db = require("../models");
const Users = db.users;
const UserDetails = db.userdetails;
const TaskTickets = db.task_tickets;
const PayoutRequests = db.userpayoutrequests;
const UserSessions = db.usersessions;
const UserAudit = db.useraudit;
const City = db.cities;
const Region = db.regions;
const Province = db.provinces;
const Op = db.Sequelize.Op;

exports.listUsers = (req,res) => {
    var detail_condition = {}
    var user_condition = {}
    var name_condition = {}

    if(req.query.first_name){detail_condition.first_name=req.query.first_name}
    if(req.query.last_name){detail_condition.last_name=req.query.last_name}
    if(req.query.email){detail_condition.email=req.query.email}
    if(req.query.mobile){detail_condition.settlement_account_number=req.query.mobile}
    if(req.query.status){user_condition.status=req.query.status}
    if(req.query.name){
        name_condition= sequelize.where(sequelize.fn('concat', sequelize.col('first_name'), ' ', sequelize.col('last_name')), {
            [Op.like]: '%' + req.query.name + '%'
        })
      }
    

    if((req.query.page)&&(req.query.count_per_page)){
        var page_number = parseInt(req.query.page);
        var count_per_page = parseInt(req.query.count_per_page);
        var skip_number_of_items = (page_number * count_per_page) - count_per_page
        delete req.query.page
        delete req.query.count_per_page
    }

    UserDetails.findAndCountAll({where:[detail_condition,name_condition],
        include: [
            {model:Users ,as:"users", where:user_condition,attributes:["status"]},
            {model:City, as:"cityData"}, {model:Province, as:"provinceData"}, {model:Region, as:"regionData"}
        ], order: [["createdAt", "DESC"]], offset:skip_number_of_items, limit: count_per_page})
    .then(userData => {
        if(userData){   
            var userDataArr = []
            userData.rows.forEach(element => {
                userDataArr.push(element.get({plain:true}))
            })
            var resultData = {}
            resultData.rows =  userDataArr.map(element => {
                console.log(element)
                if(element.regionData){
                    element.region = element.regionData.label
                    delete element.regionData
                }
                if(element.cityData){
                    element.city = element.cityData.label
                    delete element.cityData
                }
                if(element.provinceData){
                    element.province = element.provinceData.label
                    delete element.provinceData
                }
                element.status = element.users.status
                delete element.users
                return element
            })
            resultData.count = userData.count
            resultData.total_pages = Math.ceil(userData.count/count_per_page);
            resultData.current_page = page_number;
            res.send(resultData);   
        }
    })
    .catch(err => {
        console.log(err)
        res.status(500).send({
            message: err.message | "Error listing users "
        })
    })
}

exports.getUserDetails = (req,res) => {
    UserDetails.findOne({where: {user_id: req.params.user_id},
        include: [
            {model:Users ,as:"users", attributes:["status"]}, 
            {model: TaskTickets},
            {model: PayoutRequests}
        ]})
        .then(userData => {
            if(userData){
                userData = userData.get({plain:true})
                userData.status = userData.users.status
                delete userData.users
            }
            res.send(userData);   
        })
        .catch(err => {
            console.log(err)
            res.status(500).send({
                message: err.message | "error getting users details"
            })
        })
}

exports.blockUser = (req,res) => {
    var userAuditObj = {
        status_changed_to: "BLOCKED",
        last_updated_by: req.body.adminid,
        last_updated_by_type: "MERCHANT",
        user_id: req.body.user_id
    }
    if(req.body.reason){
        userAuditObj.reason = req.body.reason
    }

    Users.update({status: "BLOCKED"},{where: {user_id: req.body.user_id}})
    .then(num=>{
        if(num == 1){
            UserAudit.create(userAuditObj)
            .then(data => {
                if(data){
                    UserSessions.destroy({where: {user_id:req.body.user_id}})
                    .then(num =>{
                        res.send({
                            message: "User blocked succesfully"
                        })
                    })
                    .catch(err=>{
                        console.log("Error deleting user session")
                        console.log(err)
                    })
                }
            })
            .catch(err =>{
                console.log("Error creating user audit")
                console.log(err)
            })
        }
        else{
            res.status(500).send({
                message: "Error updating user "
            })
        }
    })
    .catch(err => {
        console.log("Error updating user")
        console.log(err)
    })
}