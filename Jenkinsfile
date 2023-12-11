/* Requires the Docker Pipeline plugin */
pipeline {
    agent { image 'php:7.2-apache' }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}