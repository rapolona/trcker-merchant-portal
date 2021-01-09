const AWS = require("aws-sdk")
const s3 = new AWS.S3({
        "accessKeyId": "AKIA6GWFARYDRSM7RV5W", 
        "secretAccessKey": "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", 
        "region": "us-east-1" 
    }
);

exports.s3Upload = (base64, fileName, bucket, metadataObj)=>{
    buf = Buffer.from(base64.replace(/^data:image\/\w+;base64,/, ""),'base64')
    var params = {
        Body:buf,
        Bucket:bucket,
        Key:fileName,
        ContentType: base64.split(":")[1].split(";")[0],
        Metadata: metadataObj || {}
    }
    var s3PutObjectPromise = s3.putObject(params).promise()
    return s3PutObjectPromise
}


exports.s3GetSignedURL = (bucket, key)=>{
    var params = {
        Bucket:bucket,
        Key:key
    }  
    try{
        var url = s3.getSignedUrl("getObject", params)
    }
    catch(err){
        console.log("CATCH ME")
        return err
    }
    return url
}

exports.s3getHeadObject = (bucket, key) => {
    var params = {
        Bucket:bucket,
        Key:key
    }   
    return s3.headObject(params).promise()
}