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
//CLASSE DA ENTIDADE mural
class cl_mural { 
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
   var $ed20_i_codigo = 0; 
   var $ed20_i_escola = 0; 
   var $ed20_c_tipo = null; 
   var $ed20_d_data_dia = null; 
   var $ed20_d_data_mes = null; 
   var $ed20_d_data_ano = null; 
   var $ed20_d_data = null; 
   var $ed20_c_assunto = null; 
   var $ed20_t_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed20_i_codigo = int8 = Código 
                 ed20_i_escola = int8 = Escola 
                 ed20_c_tipo = char(20) = Tipo 
                 ed20_d_data = date = Data 
                 ed20_c_assunto = char(50) = Assunto 
                 ed20_t_descr = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_mural() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mural"); 
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
       $this->ed20_i_codigo = ($this->ed20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]:$this->ed20_i_codigo);
       $this->ed20_i_escola = ($this->ed20_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_escola"]:$this->ed20_i_escola);
       $this->ed20_c_tipo = ($this->ed20_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_tipo"]:$this->ed20_c_tipo);
       if($this->ed20_d_data == ""){
         $this->ed20_d_data_dia = ($this->ed20_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_data_dia"]:$this->ed20_d_data_dia);
         $this->ed20_d_data_mes = ($this->ed20_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_data_mes"]:$this->ed20_d_data_mes);
         $this->ed20_d_data_ano = ($this->ed20_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_data_ano"]:$this->ed20_d_data_ano);
         if($this->ed20_d_data_dia != ""){
            $this->ed20_d_data = $this->ed20_d_data_ano."-".$this->ed20_d_data_mes."-".$this->ed20_d_data_dia;
         }
       }
       $this->ed20_c_assunto = ($this->ed20_c_assunto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_assunto"]:$this->ed20_c_assunto);
       $this->ed20_t_descr = ($this->ed20_t_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_t_descr"]:$this->ed20_t_descr);
     }else{
       $this->ed20_i_codigo = ($this->ed20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]:$this->ed20_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed20_i_codigo){ 
      $this->atualizacampos();
     if($this->ed20_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed20_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "ed20_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed20_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_c_assunto == null ){ 
       $this->erro_sql = " Campo Assunto nao Informado.";
       $this->erro_campo = "ed20_c_assunto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_t_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed20_t_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed20_i_codigo == "" || $ed20_i_codigo == null ){
       $result = @pg_query("select nextval('mural_ed20_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mural_ed20_i_codigo_seq do campo: ed20_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed20_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from mural_ed20_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed20_i_codigo)){
         $this->erro_sql = " Campo ed20_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed20_i_codigo = $ed20_i_codigo; 
       }
     }
     if(($this->ed20_i_codigo == null) || ($this->ed20_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed20_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mural(
                                       ed20_i_codigo 
                                      ,ed20_i_escola 
                                      ,ed20_c_tipo 
                                      ,ed20_d_data 
                                      ,ed20_c_assunto 
                                      ,ed20_t_descr 
                       )
                values (
                                $this->ed20_i_codigo 
                               ,$this->ed20_i_escola 
                               ,'$this->ed20_c_tipo' 
                               ,".($this->ed20_d_data == "null" || $this->ed20_d_data == ""?"null":"'".$this->ed20_d_data."'")." 
                               ,'$this->ed20_c_assunto' 
                               ,'$this->ed20_t_descr' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Mural ($this->ed20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Mural já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Mural ($this->ed20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed20_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006117,'$this->ed20_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006117,'','".AddSlashes(pg_result($resaco,0,'ed20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006118,'','".AddSlashes(pg_result($resaco,0,'ed20_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006120,'','".AddSlashes(pg_result($resaco,0,'ed20_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006119,'','".AddSlashes(pg_result($resaco,0,'ed20_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006121,'','".AddSlashes(pg_result($resaco,0,'ed20_c_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006012,1006122,'','".AddSlashes(pg_result($resaco,0,'ed20_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed20_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mural set ";
     $virgula = "";
     if(trim($this->ed20_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"])){ 
       $sql  .= $virgula." ed20_i_codigo = $this->ed20_i_codigo ";
       $virgula = ",";
       if(trim($this->ed20_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed20_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_escola"])){ 
       $sql  .= $virgula." ed20_i_escola = $this->ed20_i_escola ";
       $virgula = ",";
       if(trim($this->ed20_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed20_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_tipo"])){ 
       $sql  .= $virgula." ed20_c_tipo = '$this->ed20_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed20_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "ed20_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed20_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed20_d_data = '$this->ed20_d_data' ";
       $virgula = ",";
       if(trim($this->ed20_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed20_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_data_dia"])){ 
         $sql  .= $virgula." ed20_d_data = null ";
         $virgula = ",";
         if(trim($this->ed20_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed20_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed20_c_assunto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_assunto"])){ 
       $sql  .= $virgula." ed20_c_assunto = '$this->ed20_c_assunto' ";
       $virgula = ",";
       if(trim($this->ed20_c_assunto) == null ){ 
         $this->erro_sql = " Campo Assunto nao Informado.";
         $this->erro_campo = "ed20_c_assunto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_t_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_t_descr"])){ 
       $sql  .= $virgula." ed20_t_descr = '$this->ed20_t_descr' ";
       $virgula = ",";
       if(trim($this->ed20_t_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed20_t_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed20_i_codigo!=null){
       $sql .= " ed20_i_codigo = $this->ed20_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed20_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006117,'$this->ed20_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006117,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_codigo'))."','$this->ed20_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_escola"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006118,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_escola'))."','$this->ed20_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_tipo"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006120,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_tipo'))."','$this->ed20_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006119,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_d_data'))."','$this->ed20_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_assunto"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006121,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_assunto'))."','$this->ed20_c_assunto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_t_descr"]))
           $resac = pg_query("insert into db_acount values($acount,1006012,1006122,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_t_descr'))."','$this->ed20_t_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mural nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Mural nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed20_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed20_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006117,'$ed20_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006117,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006118,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006120,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006119,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006121,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006012,1006122,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mural
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed20_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed20_i_codigo = $ed20_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mural nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Mural nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed20_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:mural";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mural ";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = mural.ed20_i_escola";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = escolas.ed02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where mural.ed20_i_codigo = $ed20_i_codigo "; 
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
   function sql_query_file ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mural ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where mural.ed20_i_codigo = $ed20_i_codigo "; 
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