<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE trocaserie
class cl_trocaserie { 
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
   var $ed101_i_codigo = 0; 
   var $ed101_i_aluno = 0; 
   var $ed101_i_turmaorig = 0; 
   var $ed101_i_turmadest = 0; 
   var $ed101_t_obs = null; 
   var $ed101_d_data_dia = null; 
   var $ed101_d_data_mes = null; 
   var $ed101_d_data_ano = null; 
   var $ed101_d_data = null; 
   var $ed101_c_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed101_i_codigo = int8 = Código 
                 ed101_i_aluno = int8 = Aluno 
                 ed101_i_turmaorig = int8 = Turma de Origem 
                 ed101_i_turmadest = int8 = Turma de Destino 
                 ed101_t_obs = text = Observações 
                 ed101_d_data = date = Data 
                 ed101_c_tipo = char(1) = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_trocaserie() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("trocaserie"); 
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
       $this->ed101_i_codigo = ($this->ed101_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_i_codigo"]:$this->ed101_i_codigo);
       $this->ed101_i_aluno = ($this->ed101_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_i_aluno"]:$this->ed101_i_aluno);
       $this->ed101_i_turmaorig = ($this->ed101_i_turmaorig == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_i_turmaorig"]:$this->ed101_i_turmaorig);
       $this->ed101_i_turmadest = ($this->ed101_i_turmadest == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_i_turmadest"]:$this->ed101_i_turmadest);
       $this->ed101_t_obs = ($this->ed101_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_t_obs"]:$this->ed101_t_obs);
       if($this->ed101_d_data == ""){
         $this->ed101_d_data_dia = ($this->ed101_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_d_data_dia"]:$this->ed101_d_data_dia);
         $this->ed101_d_data_mes = ($this->ed101_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_d_data_mes"]:$this->ed101_d_data_mes);
         $this->ed101_d_data_ano = ($this->ed101_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_d_data_ano"]:$this->ed101_d_data_ano);
         if($this->ed101_d_data_dia != ""){
            $this->ed101_d_data = $this->ed101_d_data_ano."-".$this->ed101_d_data_mes."-".$this->ed101_d_data_dia;
         }
       }
       $this->ed101_c_tipo = ($this->ed101_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_c_tipo"]:$this->ed101_c_tipo);
     }else{
       $this->ed101_i_codigo = ($this->ed101_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed101_i_codigo"]:$this->ed101_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed101_i_codigo){ 
      $this->atualizacampos();
     if($this->ed101_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed101_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_i_turmaorig == null ){ 
       $this->erro_sql = " Campo Turma de Origem nao Informado.";
       $this->erro_campo = "ed101_i_turmaorig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_i_turmadest == null ){ 
       $this->erro_sql = " Campo Turma de Destino nao Informado.";
       $this->erro_campo = "ed101_i_turmadest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed101_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed101_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "ed101_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed101_i_codigo == "" || $ed101_i_codigo == null ){
       $result = db_query("select nextval('trocaserie_ed101_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: trocaserie_ed101_i_codigo_seq do campo: ed101_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed101_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from trocaserie_ed101_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed101_i_codigo)){
         $this->erro_sql = " Campo ed101_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed101_i_codigo = $ed101_i_codigo; 
       }
     }
     if(($this->ed101_i_codigo == null) || ($this->ed101_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed101_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into trocaserie(
                                       ed101_i_codigo 
                                      ,ed101_i_aluno 
                                      ,ed101_i_turmaorig 
                                      ,ed101_i_turmadest 
                                      ,ed101_t_obs 
                                      ,ed101_d_data 
                                      ,ed101_c_tipo 
                       )
                values (
                                $this->ed101_i_codigo 
                               ,$this->ed101_i_aluno 
                               ,$this->ed101_i_turmaorig 
                               ,$this->ed101_i_turmadest 
                               ,'$this->ed101_t_obs' 
                               ,".($this->ed101_d_data == "null" || $this->ed101_d_data == ""?"null":"'".$this->ed101_d_data."'")." 
                               ,'$this->ed101_c_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro das trocas de série de alunos ($this->ed101_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro das trocas de série de alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro das trocas de série de alunos ($this->ed101_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed101_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009029,'$this->ed101_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010160,1009029,'','".AddSlashes(pg_result($resaco,0,'ed101_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009030,'','".AddSlashes(pg_result($resaco,0,'ed101_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009031,'','".AddSlashes(pg_result($resaco,0,'ed101_i_turmaorig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009032,'','".AddSlashes(pg_result($resaco,0,'ed101_i_turmadest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009033,'','".AddSlashes(pg_result($resaco,0,'ed101_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009034,'','".AddSlashes(pg_result($resaco,0,'ed101_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010160,1009059,'','".AddSlashes(pg_result($resaco,0,'ed101_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed101_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update trocaserie set ";
     $virgula = "";
     if(trim($this->ed101_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_codigo"])){ 
       $sql  .= $virgula." ed101_i_codigo = $this->ed101_i_codigo ";
       $virgula = ",";
       if(trim($this->ed101_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed101_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_aluno"])){ 
       $sql  .= $virgula." ed101_i_aluno = $this->ed101_i_aluno ";
       $virgula = ",";
       if(trim($this->ed101_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed101_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_i_turmaorig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_turmaorig"])){ 
       $sql  .= $virgula." ed101_i_turmaorig = $this->ed101_i_turmaorig ";
       $virgula = ",";
       if(trim($this->ed101_i_turmaorig) == null ){ 
         $this->erro_sql = " Campo Turma de Origem nao Informado.";
         $this->erro_campo = "ed101_i_turmaorig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_i_turmadest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_turmadest"])){ 
       $sql  .= $virgula." ed101_i_turmadest = $this->ed101_i_turmadest ";
       $virgula = ",";
       if(trim($this->ed101_i_turmadest) == null ){ 
         $this->erro_sql = " Campo Turma de Destino nao Informado.";
         $this->erro_campo = "ed101_i_turmadest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed101_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_t_obs"])){ 
       $sql  .= $virgula." ed101_t_obs = '$this->ed101_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed101_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed101_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed101_d_data = '$this->ed101_d_data' ";
       $virgula = ",";
       if(trim($this->ed101_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed101_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_d_data_dia"])){ 
         $sql  .= $virgula." ed101_d_data = null ";
         $virgula = ",";
         if(trim($this->ed101_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed101_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed101_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed101_c_tipo"])){ 
       $sql  .= $virgula." ed101_c_tipo = '$this->ed101_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed101_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "ed101_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed101_i_codigo!=null){
       $sql .= " ed101_i_codigo = $this->ed101_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed101_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009029,'$this->ed101_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009029,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_i_codigo'))."','$this->ed101_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009030,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_i_aluno'))."','$this->ed101_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_turmaorig"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009031,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_i_turmaorig'))."','$this->ed101_i_turmaorig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_i_turmadest"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009032,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_i_turmadest'))."','$this->ed101_i_turmadest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009033,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_t_obs'))."','$this->ed101_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009034,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_d_data'))."','$this->ed101_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed101_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1010160,1009059,'".AddSlashes(pg_result($resaco,$conresaco,'ed101_c_tipo'))."','$this->ed101_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das trocas de série de alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das trocas de série de alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed101_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed101_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed101_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009029,'$ed101_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010160,1009029,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009030,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009031,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_i_turmaorig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009032,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_i_turmadest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009033,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009034,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010160,1009059,'','".AddSlashes(pg_result($resaco,$iresaco,'ed101_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from trocaserie
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed101_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed101_i_codigo = $ed101_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das trocas de série de alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed101_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das trocas de série de alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed101_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed101_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:trocaserie";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed101_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from trocaserie ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = trocaserie.ed101_i_aluno";
     $sql .= "      inner join turma      as turmaorig on  turmaorig.ed57_i_codigo = trocaserie.ed101_i_turmaorig";
     $sql .= "      inner join escola     as escolaorig  on  escolaorig.ed18_i_codigo = turmaorig.ed57_i_escola";
     $sql .= "      inner join turno      as turnoorig  on  turnoorig.ed15_i_codigo = turmaorig.ed57_i_turno";
     $sql .= "      inner join sala       as salaorig  on  salaorig.ed16_i_codigo = turmaorig.ed57_i_sala";
     $sql .= "      inner join calendario as calendarioorig  on  calendarioorig.ed52_i_codigo = turmaorig.ed57_i_calendario";
     $sql .= "      inner join base       as baseorig  on  baseorig.ed31_i_codigo = turmaorig.ed57_i_base";
     $sql .= "      inner join turma      as turmadest on  turmadest.ed57_i_codigo = trocaserie.ed101_i_turmadest";
     $sql .= "      inner join escola     as escoladest  on   escoladest.ed18_i_codigo = turmadest.ed57_i_escola";
     $sql .= "      inner join turno      as turnodest  on   turnodest.ed15_i_codigo = turmadest.ed57_i_turno";
     $sql .= "      inner join sala       as saladest  on   saladest.ed16_i_codigo = turmadest.ed57_i_sala";
     $sql .= "      inner join calendario as calendariodest  on   calendariodest.ed52_i_codigo = turmadest.ed57_i_calendario";
     $sql .= "      inner join base       as basedest  on   basedest.ed31_i_codigo = turmadest.ed57_i_base";
     $sql2 = "";
     if($dbwhere==""){
       if($ed101_i_codigo!=null ){
         $sql2 .= " where trocaserie.ed101_i_codigo = $ed101_i_codigo "; 
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
   function sql_query_file ( $ed101_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from trocaserie ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed101_i_codigo!=null ){
         $sql2 .= " where trocaserie.ed101_i_codigo = $ed101_i_codigo "; 
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

  
  function sql_query_certificado_conclusao ($ed101_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from trocaserie ";
    $sql .= "      inner join aluno                                         on aluno.ed47_i_codigo = trocaserie.ed101_i_aluno";
    $sql .= "      inner join turma               as turmaorig              on turmaorig.ed57_i_codigo = trocaserie.ed101_i_turmaorig";
    $sql .= "      inner join escola              as escolaorig             on escolaorig.ed18_i_codigo = turmaorig.ed57_i_escola";
    $sql .= "      inner join turno               as turnoorig              on turnoorig.ed15_i_codigo = turmaorig.ed57_i_turno";
    $sql .= "      inner join sala                as salaorig               on salaorig.ed16_i_codigo = turmaorig.ed57_i_sala";
    $sql .= "      inner join turmaserieregimemat as tsorigem               on tsorigem.ed220_i_turma = turmaorig.ed57_i_codigo ";
    $sql .= "      inner join serieregimemat      as serieorigem            on serieorigem.ed223_i_codigo = tsorigem.ed220_i_serieregimemat ";
    $sql .= "      inner join serie               as serieorig              on serieorig.ed11_i_codigo = serieorigem.ed223_i_serie";
    $sql .= "      inner join calendario          as calendarioorig         on calendarioorig.ed52_i_codigo = turmaorig.ed57_i_calendario";
    $sql .= "      inner join base                as baseorig               on baseorig.ed31_i_codigo = turmaorig.ed57_i_base";
    $sql .= "      inner join formaavaliacao      as formaavaliacaoorigem   on formaavaliacaoorigem.ed37_i_escola = escolaorig.ed18_i_codigo";
    $sql .= "      inner join procedimento        as procedimentoorig       on procedimentoorig.ed40_i_formaavaliacao = formaavaliacaoorigem.ed37_i_codigo";
    $sql .= "      inner join turma               as turmadest              on turmadest.ed57_i_codigo = trocaserie.ed101_i_turmadest";
    $sql .= "      inner join escola              as escoladest             on escoladest.ed18_i_codigo = turmadest.ed57_i_escola";
    $sql .= "      inner join turno               as turnodest              on turnodest.ed15_i_codigo = turmadest.ed57_i_turno";
    $sql .= "      inner join sala                as saladest               on saladest.ed16_i_codigo = turmadest.ed57_i_sala";
    $sql .= "      inner join turmaserieregimemat as tsdestino              on tsdestino.ed220_i_turma = turmadest.ed57_i_codigo ";
    $sql .= "      inner join serieregimemat      as seriedestino           on seriedestino.ed223_i_codigo = tsdestino.ed220_i_serieregimemat ";
    $sql .= "      inner join serie               as seriedest              on seriedest.ed11_i_codigo = seriedestino.ed223_i_serie";
    $sql .= "      inner join calendario          as calendariodest         on calendariodest.ed52_i_codigo = turmadest.ed57_i_calendario";
    $sql .= "      inner join base                as basedest               on basedest.ed31_i_codigo = turmadest.ed57_i_base";
    $sql .= "      inner join formaavaliacao      as formaavaliacaodestino  on formaavaliacaodestino.ed37_i_escola = escoladest.ed18_i_codigo";
    $sql .= "      inner join procedimento        as procedimentodest       on procedimentodest.ed40_i_formaavaliacao = formaavaliacaodestino.ed37_i_codigo";
    
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($ed101_i_codigo != null) {
        $sql2 .= " where trocaserie.ed101_i_codigo = $ed101_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>