const db = require("../models");
const sequelize_fixtures = require('sequelize-fixtures');
const Merchant = db.merchants;
const Branch = db.branches;
const Product = db.products;
const Op = db.Sequelize.Op;
const s3Util = require("../utils/s3.utils.js");
const moment = require("moment")

// Create and Save a new Merchant
exports.create = (req, res) => {
    // Validate request
    if (!req.body.name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a merchant
    const merchant = {
      name: req.body.name,
      address: req.body.address,
      trade_name: req.body.trade_name,
      sector: req.body.sector,
      business_structure: req.body.business_structure,
      authorized_representative: req.body.authorized_representative,
      position: req.body.position,
      contact_person: req.body.contact_person,
      contact_number: req.body.contact_number,
      email_address: req.body.email_address,
      business_nature: req.body.business_nature,
      product_type: req.body.product_type
    };

    console.log(merchant)
  
    // Save Merchant in the database
    Merchant.create(merchant)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Merchant."
        });
      });
  };

// Retrieve all Merchants from the database.
exports.findAll = (req, res) => {
    const name = req.query.name;
    var condition = name ? { name: { [Op.like]: `%${name}%` } } : null;
  
    Merchant.findAll({ where: condition, include: Product })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving merchants."
        });
      });
  };

// Find a single Merchant with an id
exports.findOne = (req, res) => {
    const id = req.body.merchantid;
    console.log(id)
    var result = {}
    Merchant.findByPk(id)
      .then(data => {
        var result = data.get({plain:true})
        if(data){
          console.log(data)
          if(data.profile_image){
            s3Util.s3getHeadObject("dev-trcker-merchant-images", "ProfileImages/"+result.profile_image)
            .then(data => {
              var signedProfileImageURL = s3Util.s3GetSignedURL("dev-trcker-merchant-images", "ProfileImages/"+result.profile_image)
              console.log(signedProfileImageURL)
              result.profile_image_url = signedProfileImageURL
              res.send(result)
            })
            .catch(err => {
              res.status(500).send({
                message: err.code
              });
            })
         }
         else{
           res.send(result)
         }
       }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Merchant with id=" + id
        });
      });
  };

// Update a Merchant by the id in the request
exports.update = (req, res) => {
    const id = req.body.merchantid;
    const now = moment().format('XX')
    if(req.body.profile_image_name && req.body.profile_image_base64){
      req.body.profile_image = "Profile_"+id+"_"+now+"_"+req.body.profile_image_name
    }
    Merchant.update(req.body, {
      where: { merchant_id: id }
    })
      .then(num => {
        if (num == 1) {
          if(req.body.profile_image_base64){
            var s3UploadData = s3Util.s3Upload(req.body.profile_image_base64, "ProfileImages/"+req.body.profile_image, "dev-trcker-merchant-images", {})
            console.log(s3UploadData)
            s3UploadData.then(() => {
              res.send({
                      message: "Merchant was updated successfully."
                    });
            })
            .catch(err=>{
              res.status(500).send({
                      message: err.message || "Error uploading image to s3"
                    })
            })
          }
          else{
            res.send({
              message: "Merchant was updated successfully."
            });
          }
        } else {
          res.status(422).send({
            message: `Cannot update Merchant with id=${id}. Maybe Merchant was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: err.message || "Error updating Merchant with id=" + id
        });
      });
  };

// Delete a Merchant with the specified id in the request
exports.delete = (req, res) => {
    const id = req.body.merchant_id;
  
    Merchant.destroy({
      where: { merchant_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Merchant was deleted successfully!"
          });
        } else {
          res.status(422).send({
            message: `Cannot delete Merchant with id=${id}. Maybe Merchant was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Merchant with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Merchant.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Merchants were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Merchants."
        });
      });
  };

  exports.loadData = (req, res) => {
    sequelize_fixtures.loadFile('app/fixtures/merchant_data.json', db).then(function(){
      Merchant.findAll({include: Branch, Product})
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving merchants."
        });
      });
  });
};

  exports.loadTasks = (req, res) => {
    sequelize_fixtures.loadFile('app/fixtures/task_data.json', db).then(function(){
      Merchant.findAll({include: Branch, Product})
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving merchants."
        });
      });
  });
};

