���������������肪�Ƃ��������܂��B
�z�e���A���ق̗\��Ǘ��A�h���q�Ǘ��V�X�e����z�肵�č쐬���܂����B

�J����
XAMPP
PHP Version 7.2.10

�J������
50����

------------------------------------------------------------------------
�f�[�^�x�[�X�\��

�f�[�^�[�x�[�X��
reservation

�e�[�u����
reservation
�J������        �^        ����
reservation_id  int       �\��ID  ��L�[   AUTO_INCREMENT
guest_id        int       �h���qID
room            int       �����ԍ�
check_in        date�@�@�@�`�F�b�N�C���\����@
check_out       date      �`�F�b�N�A�E�g�\���
adult           int       ��l�̐l��
children        int       ���l�̐l��
memo            varchar   ���l��


�e�[�u����
guest		
�J������        �^        ����
guest_id        int       �h���qID  ��L�[   AUTO_INCREMENT
name            varchar   ����
address	        varchar   �Z��
tel             varchar   �d�b�ԍ�
memo            varchar   ���l��
--------------------------------------------------------------------------
�t�@�C������

DbManager.php   �f�[�^�[�x�[�X�ڑ������L��
function.php    �G���[�`�F�b�N�Ɏg���Ă��郆�[�U�[��`�֐����L��
guest_data.php  �V�K�h���q�̓o�^�A�����h���q�̍X�V�A�폜
guest_search.php  �h���q�������
index.php         ���C�����j���[  �������Ƃɗ\��̗L�����\�������B
insert_guest.php  �h���q�̐V�K�o�^�A�X�V�A�폜�������s��
insert_reservation.php  �\��̐V�K�o�^�A�X�V�A�폜�������s��
reservation_data.php    �����̗\��f�[�^�̏ڍׂ�\������B�\��f�[�^�̍X�V�ƍ폜
reservation_new.php     �V�K�̗\��f�[�^��o�^����

---------------------------------------------------------------------------
