const db = require("../models");
const sequelize_fixtures = require('sequelize-fixtures');
const Merchant = db.merchants;
const Branch = db.branches;
const Product = db.products;
const Op = db.Sequelize.Op;

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
    console.log('dingalong')
    
    console.log(id)
    Merchant.findByPk(id)
      .then(data => {
        res.send(data);
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
    console.log(req.body)
    Merchant.update(req.body, {
      where: { merchant_id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Merchant was updated successfully."
          });
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

