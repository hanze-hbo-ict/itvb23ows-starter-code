pipeline {

    agent any

    stages {

        stage("build") {

            steps {
                sh 'echo "building"'
                sh 'docker-compose build'
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