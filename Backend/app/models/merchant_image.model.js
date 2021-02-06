const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Merchant_image = sequelize.define("merchant_image", {
      merchant_image_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      label: {
        type: Sequelize.STRING
      },
      file_name: {
        type: Sequelize.STRING(255)
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: true,
        references: {         
          model: 'merchants',
          key: 'merchant_id'
        }  
      }
    });
    return Merchant_image;
  };