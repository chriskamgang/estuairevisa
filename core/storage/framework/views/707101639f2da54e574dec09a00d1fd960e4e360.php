<?php $__env->startSection('content'); ?>
 <div class="container">
    <div class="error">404</div>
    <div class="message"><?php echo e(__('Oops! Page not found')); ?></div>
    <a href="<?php echo e(route('home')); ?>" class="home-btn"><?php echo e(__('Back To Home')); ?></a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
    body {
      height: 100vh;
      background: var(--primary-two);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      overflow: hidden;
    }
    
    .container {
      text-align: center;
      animation: fadeIn 1.5s ease-in;
    }
    
    .error {
      font-size: 10rem;
      text-shadow: 0 0 20px var(--primary), 0 0 40px var(--primary);
      animation: glow 2s ease-in-out infinite alternate;
    }
    
    .message {
      font-size: 2rem;
      margin: 20px 0;
    }
    
    .home-btn {
      padding: 12px 30px;
      font-size: 1rem;
      border: none;
      background: var(--primary);
      color: #fff;
      cursor: pointer;
      border-radius: 30px;
      transition: background 0.3s ease;
    }
    
    .home-btn:hover {
      background: var(--primary);
      color: #fff;
    }
    
    @keyframes  glow {
      from {
        text-shadow: 0 0 10px var(--primary), 0 0 20px var(--primary);
      }
      to {
        text-shadow: 0 0 40px var(--primary), 0 0 80px var(--primary);
      }
    }
    
    @keyframes  fadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @media (max-width: 600px) {
      .error {
        font-size: 6rem;
      }
      .message {
        font-size: 1.2rem;
      }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/errors/404.blade.php ENDPATH**/ ?>