pipeline {

    agent {
        docker {image 'php:8.3.1-alpine3.19'}
    }

    stages {

        stage("build") {

            steps {
                sh 'echo "building"'
                sh 'php --version'
            }

        }

        stage("test") {

            steps {
                sh 'echo "testing"'
            }

        }

        stage("deploy") {

            steps {
                sh 'echo "deploying"'
            }

        }
    }
    post {
        always {
            deleteDir()
        }
    }
}