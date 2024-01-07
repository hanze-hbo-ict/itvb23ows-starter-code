pipeline {
    agent { docker { image 'php:8.2-apache' } }
    stages {
        stage('build') {
            steps {
                 sh 'php --version'
                 echo "Building"
            }

        }
        stage("testing") {
            steps {
                echo "Testing the build"
            }
        }
        stage("Deploy") {
            steps {
                echo "Deploying"
            }
        }
    }
    post {
        success {
            echo "Build is successful"
        }
        failure {
            echo "Build is failed, see console for details"
        }
    }
}