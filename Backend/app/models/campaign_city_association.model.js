const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_city_association = sequelize.define("campaign_city_association", {
    campaign_city_association_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
    },
    campaign_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         
            model: 'campaigns',
            key: 'campaign_id'
        }  
    },
    city_id:{
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {         
            model: 'cities',
            key: 'Id'
        }
    }
    });
    return Campaign_city_association;
  };