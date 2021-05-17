const { sequelize } = require("../models");
const db = require("../models");
const UserDetail = db.userdetails;
const City = db.cities;
const Province = db.provinces;
const Region = db.regions;


exports.update = (req, res) => {
    UserDetail.update(req.body, {where:{user_id: req.body.user_id}})
    .then(num => {
        if(num == 1) {
            UserDetail.findOne({where:{user_id: req.body.user_id},
                include: [{model:City, as:"cityData"}, {model:Province, as:"provinceData"}, {model:Region, as:"regionData"}]})
            .then(data => {
                if(data){
                    var sqlData = data.get({plain:true})
                    if(sqlData.regionData){
                        sqlData.region = sqlData.regionData.label
                        delete sqlData.regionData
                    }
                    if(sqlData.cityData){
                        sqlData.city = sqlData.cityData.label
                        delete sqlData.cityData
                    }
                    if(sqlData.provinceData){
                        sqlData.province = sqlData.provinceData.label
                        delete sqlData.provinceData
                    }
                    res.send(sqlData)
                }
                else{
                    res.status(422).send({
                        message: "Unable to retrieve user details"
                    })
                }
            })
        }
        else{
            res.status(422).send({message: "No User updated"})
        }
    })
    .catch(err=>{
        console.log(err)
        res.status(500).send({
            message: err.message | "Error updating user details"
        })
    })
}

exports.bulkUpdate = (req, res) => {

    UserDetail.bulkCreate(req.body.Records, {updateOnDuplicate: ['first_name','last_name','account_level','settlement_account_number','settlement_account_type','email','cityId','provinceId','regionId','birthday','gender','country','age']})
    .then(data => {
        res.send(data);
    })
    .catch(err => {
    res.status(500).send({
        message:
        err.message || "Some error occurred while creating the Campaign."
    });
    });


}


