const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Branch = sequelize.define("branch", {
      branch_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      name: {
        type: Sequelize.STRING,
        unique:true,
        allowNull: false
      },
      address: {
        type: Sequelize.STRING
      },
      city: {
        type: Sequelize.STRING
      },
      latitude: {
        type: Sequelize.STRING
      },
      longitude: {
        type: Sequelize.STRING
      },
      photo_url: {
        type: Sequelize.STRING
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Branches belongsTo Merchant N:1
          model: 'merchants',
          key: 'merchant_id'
        }  
      }
    });
    return Branch;
  };