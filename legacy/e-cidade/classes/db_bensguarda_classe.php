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

//MODULO: patrim
//CLASSE DA ENTIDADE bensguarda
class cl_bensguarda { 
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
   var $t21_codigo = 0; 
   var $t21_numcgm = 0; 
   var $t21_tipoguarda = 0; 
   var $t21_data_dia = null; 
   var $t21_data_mes = null; 
   var $t21_data_ano = null; 
   var $t21_data = null; 
   var $t21_obs = null; 
   var $t21_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t21_codigo = int4 = Cod. Guarda 
                 t21_numcgm = int4 = Responsável 
                 t21_tipoguarda = int4 = Tipo de Guarda 
                 t21_data = date = Data da Guarda 
                 t21_obs = text = Observação 
                 t21_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_bensguarda() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensguarda"); 
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
       $this->t21_codigo = ($this->t21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_codigo"]:$this->t21_codigo);
       $this->t21_numcgm = ($this->t21_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_numcgm"]:$this->t21_numcgm);
       $this->t21_tipoguarda = ($this->t21_tipoguarda == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_tipoguarda"]:$this->t21_tipoguarda);
       if($this->t21_data == ""){
         $this->t21_data_dia = ($this->t21_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_data_dia"]:$this->t21_data_dia);
         $this->t21_data_mes = ($this->t21_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_data_mes"]:$this->t21_data_mes);
         $this->t21_data_ano = ($this->t21_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_data_ano"]:$this->t21_data_ano);
         if($this->t21_data_dia != ""){
            $this->t21_data = $this->t21_data_ano."-".$this->t21_data_mes."-".$this->t21_data_dia;
         }
       }
       $this->t21_obs = ($this->t21_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_obs"]:$this->t21_obs);
       $this->t21_instit = ($this->t21_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_instit"]:$this->t21_instit);
     }else{
       $this->t21_codigo = ($this->t21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t21_codigo"]:$this->t21_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t21_codigo){ 
      $this->atualizacampos();
     if($this->t21_numcgm == null ){ 
       $this->erro_sql = " Campo Responsável nao Informado.";
       $this->erro_campo = "t21_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t21_tipoguarda == null ){ 
       $this->erro_sql = " Campo Tipo de Guarda nao Informado.";
       $this->erro_campo = "t21_tipoguarda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t21_data == null ){ 
       $this->erro_sql = " Campo Data da Guarda nao Informado.";
       $this->erro_campo = "t21_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t21_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t21_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t21_codigo == "" || $t21_codigo == null ){
       $result = db_query("select nextval('bensguarda_t21_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensguarda_t21_codigo_seq do campo: t21_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t21_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensguarda_t21_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t21_codigo)){
         $this->erro_sql = " Campo t21_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t21_codigo = $t21_codigo; 
       }
     }
     if(($this->t21_codigo == null) || ($this->t21_codigo == "") ){ 
       $this->erro_sql = " Campo t21_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensguarda(
                                       t21_codigo 
                                      ,t21_numcgm 
                                      ,t21_tipoguarda 
                                      ,t21_data 
                                      ,t21_obs 
                                      ,t21_instit 
                       )
                values (
                                $this->t21_codigo 
                               ,$this->t21_numcgm 
                               ,$this->t21_tipoguarda 
                               ,".($this->t21_data == "null" || $this->t21_data == ""?"null":"'".$this->t21_data."'")." 
                               ,'$this->t21_obs' 
                               ,$this->t21_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações referentes a guarda do bem ($this->t21_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações referentes a guarda do bem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações referentes a guarda do bem ($this->t21_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t21_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t21_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8954,'$this->t21_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1533,8954,'','".AddSlashes(pg_result($resaco,0,'t21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1533,8955,'','".AddSlashes(pg_result($resaco,0,'t21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1533,8972,'','".AddSlashes(pg_result($resaco,0,'t21_tipoguarda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1533,8956,'','".AddSlashes(pg_result($resaco,0,'t21_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1533,8957,'','".AddSlashes(pg_result($resaco,0,'t21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1533,9823,'','".AddSlashes(pg_result($resaco,0,'t21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t21_codigo=null) { 
      $this->atualizacampos();
     $sql = " update bensguarda set ";
     $virgula = "";
     if(trim($this->t21_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_codigo"])){ 
       $sql  .= $virgula." t21_codigo = $this->t21_codigo ";
       $virgula = ",";
       if(trim($this->t21_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Guarda nao Informado.";
         $this->erro_campo = "t21_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t21_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_numcgm"])){ 
       $sql  .= $virgula." t21_numcgm = $this->t21_numcgm ";
       $virgula = ",";
       if(trim($this->t21_numcgm) == null ){ 
         $this->erro_sql = " Campo Responsável nao Informado.";
         $this->erro_campo = "t21_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t21_tipoguarda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_tipoguarda"])){ 
       $sql  .= $virgula." t21_tipoguarda = $this->t21_tipoguarda ";
       $virgula = ",";
       if(trim($this->t21_tipoguarda) == null ){ 
         $this->erro_sql = " Campo Tipo de Guarda nao Informado.";
         $this->erro_campo = "t21_tipoguarda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t21_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t21_data_dia"] !="") ){ 
       $sql  .= $virgula." t21_data = '$this->t21_data' ";
       $virgula = ",";
       if(trim($this->t21_data) == null ){ 
         $this->erro_sql = " Campo Data da Guarda nao Informado.";
         $this->erro_campo = "t21_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t21_data_dia"])){ 
         $sql  .= $virgula." t21_data = null ";
         $virgula = ",";
         if(trim($this->t21_data) == null ){ 
           $this->erro_sql = " Campo Data da Guarda nao Informado.";
           $this->erro_campo = "t21_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t21_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_obs"])){ 
       $sql  .= $virgula." t21_obs = '$this->t21_obs' ";
       $virgula = ",";
     }
     if(trim($this->t21_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t21_instit"])){ 
       $sql  .= $virgula." t21_instit = $this->t21_instit ";
       $virgula = ",";
       if(trim($this->t21_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t21_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t21_codigo!=null){
       $sql .= " t21_codigo = $this->t21_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t21_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8954,'$this->t21_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1533,8954,'".AddSlashes(pg_result($resaco,$conresaco,'t21_codigo'))."','$this->t21_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1533,8955,'".AddSlashes(pg_result($resaco,$conresaco,'t21_numcgm'))."','$this->t21_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_tipoguarda"]))
           $resac = db_query("insert into db_acount values($acount,1533,8972,'".AddSlashes(pg_result($resaco,$conresaco,'t21_tipoguarda'))."','$this->t21_tipoguarda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_data"]))
           $resac = db_query("insert into db_acount values($acount,1533,8956,'".AddSlashes(pg_result($resaco,$conresaco,'t21_data'))."','$this->t21_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_obs"]))
           $resac = db_query("insert into db_acount values($acount,1533,8957,'".AddSlashes(pg_result($resaco,$conresaco,'t21_obs'))."','$this->t21_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t21_instit"]))
           $resac = db_query("insert into db_acount values($acount,1533,9823,'".AddSlashes(pg_result($resaco,$conresaco,'t21_instit'))."','$this->t21_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações referentes a guarda do bem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informações referentes a guarda do bem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t21_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t21_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8954,'$t21_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1533,8954,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1533,8955,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1533,8972,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_tipoguarda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1533,8956,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1533,8957,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1533,9823,'','".AddSlashes(pg_result($resaco,$iresaco,'t21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensguarda
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t21_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t21_codigo = $t21_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações referentes a guarda do bem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Informações referentes a guarda do bem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t21_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensguarda";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguarda ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bensguarda.t21_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bensguarda.t21_instit";
     $sql .= "      inner join benstipoguarda  on  benstipoguarda.t20_codigo = bensguarda.t21_tipoguarda";
     $sql2 = "";
     if($dbwhere==""){
       if($t21_codigo!=null ){
         $sql2 .= " where bensguarda.t21_codigo = $t21_codigo "; 
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
   function sql_query_dev ( $t21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguarda ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bensguarda.t21_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bensguarda.t21_instit";
     $sql .= "      inner join benstipoguarda  on  benstipoguarda.t20_codigo = bensguarda.t21_tipoguarda";
     $sql .= "      left  join bensguardaitem  on  bensguarda.t21_codigo = bensguardaitem.t22_bensguarda";
     $sql .= "      left  join bensguardaitemdev  on  bensguardaitemdev.t23_guardaitem = bensguardaitem.t22_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($t21_codigo!=null ){
         $sql2 .= " where bensguarda.t21_codigo = $t21_codigo "; 
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
   function sql_query_file ( $t21_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensguarda ";
     $sql2 = "";
     if($dbwhere==""){
       if($t21_codigo!=null ){
         $sql2 .= " where bensguarda.t21_codigo = $t21_codigo "; 
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