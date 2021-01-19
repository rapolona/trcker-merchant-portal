const { sequelize } = require("../models");
const db = require("../models");
const Users = db.users;
const UserDetails = db.userdetails;
const TaskTickets = db.task_tickets;
const PayoutRequests = db.userpayoutrequests;
const Op = db.Sequelize.Op;

exports.listUsers = (req,res) => {
    var condition = []
    var innerCondition = {}
  
    //Check for filters
    if(req.query.name){
        condition.push(sequelize.where(sequelize.fn('concat', sequelize.col('first_name'), ' ', sequelize.col('last_name')), {
            [Op.like]: '%' + req.query.name + '%'
        }))
      }
      if(req.query.status){
        innerCondition.status = { [Op.like]: `%${req.query.status}%` }
      }
      if(req.query.email){
        condition.push({email:{[Op.like]: `%${req.query.email}%`}})
      } 
      if(req.query.mobile){
        condition.push({settlement_account_number:{[Op.like]: `%${req.query.mobile}%`}})  
      } 
      console.log(condition)


    if((req.query.page)&&(req.query.count_per_page)){
        var page_number = parseInt(req.query.page);
        var count_per_page = parseInt(req.query.count_per_page);
        var skip_number_of_items = (page_number * count_per_page) - count_per_page
    }

    UserDetails.findAndCountAll({include: [{model:Users ,as:"users", attributes:["status"], where:innerCondition}], order: [["createdAt", "DESC"]], 
        offset:skip_number_of_items, 
        limit: count_per_page,
        where: condition})
    .then(userData => {
        if(userData){   
            var userDataArr = []
            userData.rows.forEach(element => {
                userDataArr.push(element.get({plain:true}))
            })
            var resultData = {}
            resultData.rows =  userDataArr.map(element => {
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