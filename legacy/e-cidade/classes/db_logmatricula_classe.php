<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: educação
//CLASSE DA ENTIDADE logmatricula
class cl_logmatricula { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ed248_i_codigo = 0; 
   var $ed248_i_usuario = 0; 
   var $ed248_i_motivo = 0; 
   var $ed248_i_aluno = 0; 
   var $ed248_t_origem = null; 
   var $ed248_t_obs = null; 
   var $ed248_d_data_dia = null; 
   var $ed248_d_data_mes = null; 
   var $ed248_d_data_ano = null; 
   var $ed248_d_data = null; 
   var $ed248_c_hora = null; 
   var $ed248_c_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed248_i_codigo = int8 = Código 
                 ed248_i_usuario = int8 = Usuário 
                 ed248_i_motivo = int8 = Motivo da Exclusão 
                 ed248_i_aluno = int8 = Aluno 
                 ed248_t_origem = text = Matrícula 
                 ed248_t_obs = text = Observações 
                 ed248_d_data = date = Data da Exclusão 
                 ed248_c_hora = char(5) = Hora da Exclusão 
                 ed248_c_tipo = char(1) = Tipo de Movimento 
                 ";
   //funcao construtor da classe 
   function cl_logmatricula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("logmatricula"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ed248_i_codigo = ($this->ed248_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_i_codigo"]:$this->ed248_i_codigo);
       $this->ed248_i_usuario = ($this->ed248_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_i_usuario"]:$this->ed248_i_usuario);
       $this->ed248_i_motivo = ($this->ed248_i_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_i_motivo"]:$this->ed248_i_motivo);
       $this->ed248_i_aluno = ($this->ed248_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_i_aluno"]:$this->ed248_i_aluno);
       $this->ed248_t_origem = ($this->ed248_t_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_t_origem"]:$this->ed248_t_origem);
       $this->ed248_t_obs = ($this->ed248_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_t_obs"]:$this->ed248_t_obs);
       if($this->ed248_d_data == ""){
         $this->ed248_d_data_dia = ($this->ed248_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_d_data_dia"]:$this->ed248_d_data_dia);
         $this->ed248_d_data_mes = ($this->ed248_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_d_data_mes"]:$this->ed248_d_data_mes);
         $this->ed248_d_data_ano = ($this->ed248_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_d_data_ano"]:$this->ed248_d_data_ano);
         if($this->ed248_d_data_dia != ""){
            $this->ed248_d_data = $this->ed248_d_data_ano."-".$this->ed248_d_data_mes."-".$this->ed248_d_data_dia;
         }
       }
       $this->ed248_c_hora = ($this->ed248_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_c_hora"]:$this->ed248_c_hora);
       $this->ed248_c_tipo = ($this->ed248_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_c_tipo"]:$this->ed248_c_tipo);
     }else{
       $this->ed248_i_codigo = ($this->ed248_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed248_i_codigo"]:$this->ed248_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed248_i_codigo){ 
      $this->atualizacampos();
     if($this->ed248_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed248_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed248_i_motivo == null ){ 
       $this->ed248_i_motivo = "null";
     }
     if($this->ed248_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed248_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed248_t_origem == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed248_t_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed248_d_data == null ){ 
       $this->erro_sql = " Campo Data da Exclusão nao Informado.";
       $this->erro_campo = "ed248_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed248_c_hora == null ){ 
       $this->erro_sql = " Campo Hora da Exclusão nao Informado.";
       $this->erro_campo = "ed248_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed248_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Movimento nao Informado.";
       $this->erro_campo = "ed248_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed248_i_codigo == "" || $ed248_i_codigo == null ){
       $result = db_query("select nextval('logmatricula_ed248_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: logmatricula_ed248_i_codigo_seq do campo: ed248_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed248_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from logmatricula_ed248_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed248_i_codigo)){
         $this->erro_sql = " Campo ed248_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed248_i_codigo = $ed248_i_codigo; 
       }
     }
     if(($this->ed248_i_codigo == null) || ($this->ed248_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed248_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into logmatricula(
                                       ed248_i_codigo 
                                      ,ed248_i_usuario 
                                      ,ed248_i_motivo 
                                      ,ed248_i_aluno 
                                      ,ed248_t_origem 
                                      ,ed248_t_obs 
                                      ,ed248_d_data 
                                      ,ed248_c_hora 
                                      ,ed248_c_tipo 
                       )
                values (
                                $this->ed248_i_codigo 
                               ,$this->ed248_i_usuario 
                               ,$this->ed248_i_motivo 
                               ,$this->ed248_i_aluno 
                               ,'$this->ed248_t_origem' 
                               ,'$this->ed248_t_obs' 
                               ,".($this->ed248_d_data == "null" || $this->ed248_d_data == ""?"null":"'".$this->ed248_d_data."'")." 
                               ,'$this->ed248_c_hora' 
                               ,'$this->ed248_c_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log de Exclusão de Matrículas ($this->ed248_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log de Exclusão de Matrículas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log de Exclusão de Matrículas ($this->ed248_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed248_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed248_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12005,'$this->ed248_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2077,12005,'','".AddSlashes(pg_result($resaco,0,'ed248_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12008,'','".AddSlashes(pg_result($resaco,0,'ed248_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12009,'','".AddSlashes(pg_result($resaco,0,'ed248_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12011,'','".AddSlashes(pg_result($resaco,0,'ed248_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12010,'','".AddSlashes(pg_result($resaco,0,'ed248_t_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12012,'','".AddSlashes(pg_result($resaco,0,'ed248_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12013,'','".AddSlashes(pg_result($resaco,0,'ed248_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12014,'','".AddSlashes(pg_result($resaco,0,'ed248_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2077,12353,'','".AddSlashes(pg_result($resaco,0,'ed248_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed248_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update logmatricula set ";
     $virgula = "";
     if(trim($this->ed248_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_codigo"])){ 
       $sql  .= $virgula." ed248_i_codigo = $this->ed248_i_codigo ";
       $virgula = ",";
       if(trim($this->ed248_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed248_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed248_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_usuario"])){ 
       $sql  .= $virgula." ed248_i_usuario = $this->ed248_i_usuario ";
       $virgula = ",";
       if(trim($this->ed248_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed248_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed248_i_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_motivo"])){ 
        if(trim($this->ed248_i_motivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_motivo"])){ 
           $this->ed248_i_motivo = "0" ; 
        } 
       $sql  .= $virgula." ed248_i_motivo = $this->ed248_i_motivo ";
       $virgula = ",";
     }
     if(trim($this->ed248_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_aluno"])){ 
       $sql  .= $virgula." ed248_i_aluno = $this->ed248_i_aluno ";
       $virgula = ",";
       if(trim($this->ed248_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed248_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed248_t_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_t_origem"])){ 
       $sql  .= $virgula." ed248_t_origem = '$this->ed248_t_origem' ";
       $virgula = ",";
       if(trim($this->ed248_t_origem) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed248_t_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed248_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_t_obs"])){ 
       $sql  .= $virgula." ed248_t_obs = '$this->ed248_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed248_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed248_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed248_d_data = '$this->ed248_d_data' ";
       $virgula = ",";
       if(trim($this->ed248_d_data) == null ){ 
         $this->erro_sql = " Campo Data da Exclusão nao Informado.";
         $this->erro_campo = "ed248_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_d_data_dia"])){ 
         $sql  .= $virgula." ed248_d_data = null ";
         $virgula = ",";
         if(trim($this->ed248_d_data) == null ){ 
           $this->erro_sql = " Campo Data da Exclusão nao Informado.";
           $this->erro_campo = "ed248_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed248_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_c_hora"])){ 
       $sql  .= $virgula." ed248_c_hora = '$this->ed248_c_hora' ";
       $virgula = ",";
       if(trim($this->ed248_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Exclusão nao Informado.";
         $this->erro_campo = "ed248_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed248_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed248_c_tipo"])){ 
       $sql  .= $virgula." ed248_c_tipo = '$this->ed248_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed248_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Movimento nao Informado.";
         $this->erro_campo = "ed248_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed248_i_codigo!=null){
       $sql .= " ed248_i_codigo = $this->ed248_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed248_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12005,'$this->ed248_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2077,12005,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_i_codigo'))."','$this->ed248_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2077,12008,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_i_usuario'))."','$this->ed248_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_motivo"]))
           $resac = db_query("insert into db_acount values($acount,2077,12009,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_i_motivo'))."','$this->ed248_i_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,2077,12011,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_i_aluno'))."','$this->ed248_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_t_origem"]))
           $resac = db_query("insert into db_acount values($acount,2077,12010,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_t_origem'))."','$this->ed248_t_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,2077,12012,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_t_obs'))."','$this->ed248_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2077,12013,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_d_data'))."','$this->ed248_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,2077,12014,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_c_hora'))."','$this->ed248_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed248_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2077,12353,'".AddSlashes(pg_result($resaco,$conresaco,'ed248_c_tipo'))."','$this->ed248_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log de Exclusão de Matrículas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed248_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log de Exclusão de Matrículas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed248_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed248_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed248_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed248_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12005,'$ed248_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2077,12005,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12008,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12009,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12011,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12010,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_t_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12012,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12014,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2077,12353,'','".AddSlashes(pg_result($resaco,$iresaco,'ed248_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from logmatricula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed248_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed248_i_codigo = $ed248_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log de Exclusão de Matrículas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed248_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log de Exclusão de Matrículas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed248_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed248_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:logmatricula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed248_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from logmatricula ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = logmatricula.ed248_i_usuario";
     $sql .= "      left join motivoexclusao  on  motivoexclusao.ed249_i_codigo = logmatricula.ed248_i_motivo";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = logmatricula.ed248_i_aluno";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql2 = "";
     if($dbwhere==""){
       if($ed248_i_codigo!=null ){
         $sql2 .= " where logmatricula.ed248_i_codigo = $ed248_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $ed248_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from logmatricula ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed248_i_codigo!=null ){
         $sql2 .= " where logmatricula.ed248_i_codigo = $ed248_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>