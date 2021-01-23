const { userpayoutrequestsaudit } = require("../models");
const db = require("../models");
const User_payout_request = db.userpayoutrequests;
const User_Payout_Requests_Audit = db.userpayoutrequestsaudit;
const UserWallet = db.user_wallets
const UserDetails = db.userdetails;
const City = db.cities;
const Province = db.provinces;
const Region = db.regions;

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

    User_payout_request.findAndCountAll({offset:skip_number_of_items, limit: count_per_page, 
        include:[{model:UserDetails, 
            include: [{model:City, as:"cityData"}, {model:Province, as:"provinceData"}, {model:Region, as:"regionData"}]
        }]
    })
    .then(data => {
        var userPayoutArr = []
        data.rows.forEach(element => {
            userPayoutArr.push(element.get({plain:true}))
        })
        var resultData = {}
        resultData.rows =  userPayoutArr.map(element => {
            if(element.user_detail.regionData){
                element.user_detail.region = element.user_detail.regionData.label
                delete element.user_detail.regionData
            }
            if(element.user_detail.cityData){
                element.user_detail.city = element.user_detail.cityData.label
                delete element.user_detail.cityData
            }
            if(element.user_detail.provinceData){
                element.user_detail.province = element.user_detail.provinceData.label
                delete element.user_detail.provinceData
            }
            return element
        })
        resultData.count = data.count
        resultData.total_pages = Math.ceil(data.count/count_per_page);
        resultData.current_page = page_number;
        res.send(resultData);
    })
    .catch(err => {
        res.status(500).send({
        message:
            err.message || "Some error occurred while retrieving products."
        });
    });
};

//Find single payout request
exports.findOne = (req, res) => {
    const user_payout_request_id = req.params.user_payout_request_id;
    const merchant_id = req.body.merchantid;

  
    User_payout_request.findByPk(user_payout_request_id)
      .then(data => {
        
        res.send(data);
     
        
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Payout request with id=" + user_payout_request_id
        });
      });
  };

  exports.updatePayoutRequest = (req,res)=> {
    var chainedPromises = []
    var payoutRequestBody = {
        user_payout_request_id: req.body.user_payout_request_id,
    }
    console.log(req.body)
    switch(req.body.status){
        case "APPROVE":
            payoutRequestBody.status = "APPROVED";
            payoutRequestBody.reference_id = req.body.reference_id;
            break;
        case "REJECT":
            payoutRequestBody.status = "REJECTED";
            break;
        default:
            res.status(422).send({message:"Unknown status to set to"})
            return
    }
    db.sequelize.transaction({autocommit:false}, transaction => {
        chainedPromises.push(
            User_payout_request.update(payoutRequestBody, {where: {user_payout_request_id: req.body.user_payout_request_id, status:"PENDING"}, transaction})
            .then(num => {
                if(num != 1){
                    res.status(422).send({message: "Updating a payout request that has already been settled"})
                    transaction.rollback();
                }
            })
            .catch(err => {
                res.status(500).send({
                    message: err.message | "Error updating user payout request"
                })
            })
        );

        if(payoutRequestBody.status == "REJECTED"){
            chainedPromises.push(
                User_payout_request.findOne({where: {user_payout_request_id: req.body.user_payout_request_id}, attributes: ["user_id", "amount"]})
            )
        }
        var userPayoutRequestAuditObj = {
            status_changed_to: payoutRequestBody.status,
            last_updated_by : req.body.adminid,
            last_updated_by_type: "MERCHANT",
            user_payout_request_id: req.body.user_payout_request_id,        
        }
        chainedPromises.push(
            User_Payout_Requests_Audit.create(userPayoutRequestAuditObj, {transaction: transaction})
            .catch(err=>{
                console.log("Error creating payout request audit")
                console.log(err)
            })
        )
        return Promise.all(chainedPromises)
        .then(data => {
            if(data[1]){
                var UserPayoutData = data[1].get({plain:true})
                UserWallet.update({
                    current_amount: db.sequelize.literal(`current_amount + ${UserPayoutData.amount}`)},
                    {where: {user_id: UserPayoutData.user_id}, 
                })
                .catch(err=>{
                    console.log(err)
                })
            }
            res.send({message: `Succesfully ${payoutRequestBody.status} payout request`})
        })
        .catch(err => {
            console.log(err)
            res.status(500).send({
                message: "Error processing payout request"
            })
        })
    })
   
}





