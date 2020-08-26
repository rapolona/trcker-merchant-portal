const db = require("../models");
const Branch = db.branches;
const Campaign_Branch_Association = db.campaign_branch_associations;
const Op = db.Sequelize.Op;

// Create and Save a new Branch
exports.create = (req, res) => {
    // Validate request
    if (!req.body.name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a branch
    const branch = {
      name: req.body.name,
      address: req.body.address,
      city: req.body.city,
      latitude: req.body.latitude,
      longitude: req.body.longitude,
      photo_url: req.body.photo_url,
      merchant_id: req.body.merchantid

    };

    console.log(branch)
  
    // Save Branch in the database
    Branch.create(branch)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Branch."
        });
      });
  };

// Retrieve all Branches from the database.
exports.findAll = (req, res) => {

  const id = req.body.merchantid;
  var condition = null;
  if(req.body.merchantid){
    condition = {merchant_id: id};
  }
  console.log(condition)

  Branch.findAll({ where: condition })
    .then(data => {
      res.send(data);
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving products."
      });
    });
};

// Find a single Branch with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Branch.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Branch with id=" + id
        });
      });
  };

// Update a Branch by the id in the request
exports.update = (req, res) => {
    const branch_id = req.body.branch_id;
  
    Branch.update(req.body, {
      where: { branch_id: branch_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Branch was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Branch with id=${id}. Maybe Branch was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Branch with id=" + id
        });
      });
  };

// Delete a Branch with the specified id in the request
exports.delete = (req, res) => {
    const id = req.body.branch_id;
    const merchant_id = req.body.merchantid;
  
    Branch.destroy({
      where: { branch_id: id, merchant_id: merchant_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Branch was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Branch with id=${id}. Maybe Branch was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Branch with id=" + id
        });
      });
  };

// Delete all Branchs from the database.
exports.deleteAll = (req, res) => {
    Branch.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Branchs were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Branchs."
        });
      });
  };

