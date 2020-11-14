const db = require("../models");
const jwt =  require("jsonwebtoken")
const PasswordUtils = require("../utils/password.utils.js");
const Admin = db.admins;
const AdminSession = db.adminsessions
const AdminDetail = db.admindetails
const AdminPasswordToken = db.adminpasswordtokens
const Merchant = db.merchants;
const mailer = require("../utils/email.utils.js");
const crypto = require("crypto");
const Op = db.sequelize.Op;
var fs = require("fs");

exports.create = (req,res) => {
    PasswordUtils.hash(req.body.password, (passwordObject)=>{
        const admin = {
            username : req.body.username,
            password: passwordObject.hash,
            password_salt: passwordObject.salt,
            merchant_id: req.body.merchant_id,
            adminDetails:{
                first_name: req.body.first_name,
                last_name: req.body.last_name,
                designation: req.body.designation,
                contact_number: req.body.contact_number,
                email_address: req.body.email_address   
            }
        };
        Admin.create(admin, {include: [{model:AdminDetail, as:"adminDetails"}]})
            .then(data => {
                res.send(data);
            })
            .catch(err => {
                res.status(500).send({
                    message:
                        err.message || "Some error occured while creating User."
                });
            });
    })
};

exports.findAdminByCredential = (req,res) => {
    Admin.findOne({where: {username: req.body.username}, attributes:['password_salt']})
        .then(data => {
            if(data){
                req.body.salt = data.get({plain:true}).password_salt
                PasswordUtils.hash(req.body, (passwordObject) => {
                    const attempt = {
                        username : req.body.username,
                        password : passwordObject.hash
                    }
                    Admin.findOne({where: attempt,include: [{model:AdminDetail, as:"adminDetails"}, {model:Merchant, as:"merchant"}], attributes:{exclude: ['password', 'password_salt']}})
                    .then(data => {
                        if(data){
                            var sqlData = data.get({plain:true})
                            var token = jwt.sign({username: sqlData.username,adminid: sqlData.admin_id, merchantid: sqlData.merchant_id},"TrckerTestSecret", {expiresIn: '1d'})
                            const adminsession = {
                                admin_id : sqlData.admin_id,
                                token : token,
                            }
                            console.log(adminsession)
                            AdminSession.findOne({where: {"admin_id" : sqlData.admin_id}})
                            .then(data => {
                                if(data){
                                    AdminSession.update({token: token}, {where: {admin_id : sqlData.admin_id}})
                                    .then(num => {
                                        if(num == 1) {
                                            sqlData.token = token
                                            res.cookie("authentication", token)
                                            res.send(sqlData)
                                        }
                                        else{
                                            res.status(422).send({
                                                message:
                                                    "Admin session may not have been found"
                                            })
                                        }
                                    })
                                    .catch(err => {
                                        res.status(422).send({
                                            message:
                                                "Error updating session"
                                        })
                                    })
                                }
                                else{
                                    AdminSession.create(adminsession)
                                    .then(data => {
                                        sqlData.token = token
                                        res.cookie("authentication", token)
                                        res.send(sqlData)
                                    })
                                    .catch(err => {
                                        res.status(500).send({
                                            message:
                                            err.message || "Session error"
                                        })
                                    })
                                }
                            })
                        }                        
                        else{
                            res.status(422).send({
                                message:
                                    "Username or password is incorrect"
                            })
                        }
                    })
                    .catch(err => {
                        res.status(500).send({
                            message:
                                err.message || "Some error occured while retrieveing Users."
                        });
                    })   
            })
            } 
            else{
                res.status(422).send({
                    message:
                        "User was not found in the system"
                });
            }           
        })
        .catch(err => {
            res.status(500).send({
                message:
                    err.message || "Some error occured while retrieveing Users."
            });
        })
};

exports.refreshToken = (req,res)=>{
    var tokenData = jwt.verify(req.headers.authorization.split(' ')[1], "TrckerTestSecret");
    var token = jwt.sign({username: tokenData.username,adminid: tokenData.adminid, merchantid: tokenData.merchantid},"TrckerTestSecret", {expiresIn: '1d'});
    AdminSession.update({token: token}, {where: {admin_id : tokenData.adminid}})
    .then(num => {
        if(num == 1){
            res.cookie("authentication", token)
            res.send({token:token})
        }
        else{
            res.status(422).send({
                message: "Failed to update session"
            })
        }
    })
    .catch(err => {
        res.status(500).send({
            message:
                "Session error"
        })
    })
}


exports.forgotPassword = (req,res) => {
    var passwordToken = crypto.randomBytes(3).toString("hex").toUpperCase();
    var adminEmail = req.body.email_address;
    if(!adminEmail){
        res.status(422).send({
            message: "Please submit a valid email to check in our system"
        })
        return;
    }
    
    AdminDetail.findOne({where: {email_address: adminEmail}})
    .then(adminDetail => {
        if(adminDetail){
            AdminPasswordToken.create({admin_id: adminDetail.admin_id, token: passwordToken, status:0})
            .then(data => {
                var passwordTokenEmailTemplate = ""
                try{
                    fs.readFile("app/templates/forgotpasswordemail.html", 'utf8', 
                    (err,filedata)=>{
                        if(err){
                            console.log("While reading template : "  + err);
                        }
                        else{
                            passwordTokenEmailTemplate = filedata.replace(/%PASSWORDTOKEN%/g, passwordToken);
                            mailer.sendMail(req.body.email_address, "HUSTLE PH", "Password Reset Request", passwordTokenEmailTemplate)
                            res.send(adminDetail)
                        }
                    })
                }
                catch(err){
                    return res.status(500).send({
                        message: err.message || "Some error occured while retrieving email"
                    })
                }
            })
        }
        else{
            res.status(422).send({message: "No user found with the email specified"})
        }
    })
    .catch(err => {
        res.status(500).send({message : err.message|| "Something went wrong while forgetting password"})
    })
}

exports.verifyChangePasswordToken = (req,res)=> {
    var passwordToken = req.body.passwordToken
    var adminId = req.body.admin_id
    if(!passwordToken){
        res.status(422).send({
            message:"Password Token is required"
        })
    }
    if(!adminId){
        res.status(422).send({
            message: "One or more required field is not satisfied"
        })
    }
    AdminPasswordToken.findOne({where: {admin_id: adminId, token: passwordToken, status: 0}})
    .then(data => {
        if(data){
            AdminPasswordToken.update({status:1}, {where:{admin_id: adminId, token:passwordToken}})
            .then(num => {
                if(num == 1){
                    res.send({message: "Token accepted"})
                }
                else{
                    res.status(422).send({message: "No records matched"})
                }
            })
        }
        else{
            res.status(422).send({
                message: "Token is not associated with the user"
            })
        }
    })
}

exports.changePassword = (req, res) => {
    var passwordToken = req.body.passwordToken;
    var adminId = req.body.adminid || req.body.admin_id
    if(!passwordToken && !adminId){
        res.status(403).send({
            message: "Unauthenticated Request"
        })
        return;
    }
    else if (passwordToken){
        AdminPasswordToken.findOne({where: {admin_id: adminId, token: passwordToken, status: 1}})
        .then(data => {
            if(data){
                PasswordUtils.hash(req.body.password, (passwordObject) => {
                    const newPass = {
                        password : passwordObject.hash,
                        password_salt : passwordObject.salt
                    }
                    Admin.update(newPass, {where: {admin_id: adminId}})
                    .then(num => {
                        if(num==1){
                            res.send({message: "Admin password succesfuly changed"})
                        }
                        else{
                            res.status(422).send({message: "Admin not found in the system"})
                        }
                    })
                    .catch(err=> {
                        res.status(500).send({
                            message: err.message || "Admin password was not updated due to some error"
                        })
                    })
                })
            }
            else{
                res.status(422).send({message: "No records matched"})
            }
        })
    }
    else if (req.body.adminid){
        PasswordUtils.hash(req.body.password, (passwordObject) => {
            const newPass = {
                password : passwordObject.hash,
                password_salt : passwordObject.salt
            }
            Admin.update(newPass, {where: {admin_id: req.body.adminid}})
            .then(num => {
                if(num==1){
                    res.send({message: "Admin password succesfully changed"})
                }
                else{
                    res.status(422).send({message: "Admin not found in the system"})
                }
            })
            .catch(err=> {
                res.status(500).send({
                    message: err || "Admin password was not updated due to some error"
                })
            })
        })
    }
    
}

