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

//MODULO: cadastro
//CLASSE DA ENTIDADE averba
class cl_averba { 
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
   var $j55_codave = 0; 
   var $j55_matric = 0; 
   var $j55_data_dia = null; 
   var $j55_data_mes = null; 
   var $j55_data_ano = null; 
   var $j55_data = null; 
   var $j55_regimo = null; 
   var $j55_cidade = null; 
   var $j55_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j55_codave = int4 = Codigo Averbacao 
                 j55_matric = int4 = Matricula 
                 j55_data = date = Data 
                 j55_regimo = varchar(20) = Registro Imovel 
                 j55_cidade = varchar(20) = Cidade 
                 j55_numcgm = int4 = Número Cgm 
                 ";
   //funcao construtor da classe 
   function cl_averba() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averba"); 
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
       $this->j55_codave = ($this->j55_codave == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_codave"]:$this->j55_codave);
       $this->j55_matric = ($this->j55_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_matric"]:$this->j55_matric);
       if($this->j55_data == ""){
         $this->j55_data_dia = ($this->j55_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_data_dia"]:$this->j55_data_dia);
         $this->j55_data_mes = ($this->j55_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_data_mes"]:$this->j55_data_mes);
         $this->j55_data_ano = ($this->j55_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_data_ano"]:$this->j55_data_ano);
         if($this->j55_data_dia != ""){
            $this->j55_data = $this->j55_data_ano."-".$this->j55_data_mes."-".$this->j55_data_dia;
         }
       }
       $this->j55_regimo = ($this->j55_regimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_regimo"]:$this->j55_regimo);
       $this->j55_cidade = ($this->j55_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_cidade"]:$this->j55_cidade);
       $this->j55_numcgm = ($this->j55_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_numcgm"]:$this->j55_numcgm);
     }else{
       $this->j55_codave = ($this->j55_codave == ""?@$GLOBALS["HTTP_POST_VARS"]["j55_codave"]:$this->j55_codave);
     }
   }
   // funcao para inclusao
   function incluir ($j55_codave){ 
      $this->atualizacampos();
     if($this->j55_matric == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "j55_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j55_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j55_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j55_regimo == null ){ 
       $this->erro_sql = " Campo Registro Imovel nao Informado.";
       $this->erro_campo = "j55_regimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j55_cidade == null ){ 
       $this->erro_sql = " Campo Cidade nao Informado.";
       $this->erro_campo = "j55_cidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j55_numcgm == null ){ 
       $this->erro_sql = " Campo Número Cgm nao Informado.";
       $this->erro_campo = "j55_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j55_codave == "" || $j55_codave == null ){
       $result = db_query("select nextval('averba_j55_codave_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averba_j55_codave_seq do campo: j55_codave"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j55_codave = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from averba_j55_codave_seq");
       if(($result != false) && (pg_result($result,0,0) < $j55_codave)){
         $this->erro_sql = " Campo j55_codave maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j55_codave = $j55_codave; 
       }
     }
     if(($this->j55_codave == null) || ($this->j55_codave == "") ){ 
       $this->erro_sql = " Campo j55_codave nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averba(
                                       j55_codave 
                                      ,j55_matric 
                                      ,j55_data 
                                      ,j55_regimo 
                                      ,j55_cidade 
                                      ,j55_numcgm 
                       )
                values (
                                $this->j55_codave 
                               ,$this->j55_matric 
                               ,".($this->j55_data == "null" || $this->j55_data == ""?"null":"'".$this->j55_data."'")." 
                               ,'$this->j55_regimo' 
                               ,'$this->j55_cidade' 
                               ,$this->j55_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Averbações ($this->j55_codave) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Averbações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Averbações ($this->j55_codave) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j55_codave;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j55_codave));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,198,'$this->j55_codave','I')");
       $resac = db_query("insert into db_acount values($acount,40,198,'','".AddSlashes(pg_result($resaco,0,'j55_codave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,40,199,'','".AddSlashes(pg_result($resaco,0,'j55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,40,200,'','".AddSlashes(pg_result($resaco,0,'j55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,40,201,'','".AddSlashes(pg_result($resaco,0,'j55_regimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,40,202,'','".AddSlashes(pg_result($resaco,0,'j55_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,40,2484,'','".AddSlashes(pg_result($resaco,0,'j55_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j55_codave=null) { 
      $this->atualizacampos();
     $sql = " update averba set ";
     $virgula = "";
     if(trim($this->j55_codave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_codave"])){ 
       $sql  .= $virgula." j55_codave = $this->j55_codave ";
       $virgula = ",";
       if(trim($this->j55_codave) == null ){ 
         $this->erro_sql = " Campo Codigo Averbacao nao Informado.";
         $this->erro_campo = "j55_codave";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j55_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_matric"])){ 
       $sql  .= $virgula." j55_matric = $this->j55_matric ";
       $virgula = ",";
       if(trim($this->j55_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j55_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j55_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j55_data_dia"] !="") ){ 
       $sql  .= $virgula." j55_data = '$this->j55_data' ";
       $virgula = ",";
       if(trim($this->j55_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j55_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j55_data_dia"])){ 
         $sql  .= $virgula." j55_data = null ";
         $virgula = ",";
         if(trim($this->j55_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j55_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j55_regimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_regimo"])){ 
       $sql  .= $virgula." j55_regimo = '$this->j55_regimo' ";
       $virgula = ",";
       if(trim($this->j55_regimo) == null ){ 
         $this->erro_sql = " Campo Registro Imovel nao Informado.";
         $this->erro_campo = "j55_regimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j55_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_cidade"])){ 
       $sql  .= $virgula." j55_cidade = '$this->j55_cidade' ";
       $virgula = ",";
       if(trim($this->j55_cidade) == null ){ 
         $this->erro_sql = " Campo Cidade nao Informado.";
         $this->erro_campo = "j55_cidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j55_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j55_numcgm"])){ 
       $sql  .= $virgula." j55_numcgm = $this->j55_numcgm ";
       $virgula = ",";
       if(trim($this->j55_numcgm) == null ){ 
         $this->erro_sql = " Campo Número Cgm nao Informado.";
         $this->erro_campo = "j55_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j55_codave!=null){
       $sql .= " j55_codave = $this->j55_codave";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j55_codave));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,198,'$this->j55_codave','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_codave"]))
           $resac = db_query("insert into db_acount values($acount,40,198,'".AddSlashes(pg_result($resaco,$conresaco,'j55_codave'))."','$this->j55_codave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_matric"]))
           $resac = db_query("insert into db_acount values($acount,40,199,'".AddSlashes(pg_result($resaco,$conresaco,'j55_matric'))."','$this->j55_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_data"]))
           $resac = db_query("insert into db_acount values($acount,40,200,'".AddSlashes(pg_result($resaco,$conresaco,'j55_data'))."','$this->j55_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_regimo"]))
           $resac = db_query("insert into db_acount values($acount,40,201,'".AddSlashes(pg_result($resaco,$conresaco,'j55_regimo'))."','$this->j55_regimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_cidade"]))
           $resac = db_query("insert into db_acount values($acount,40,202,'".AddSlashes(pg_result($resaco,$conresaco,'j55_cidade'))."','$this->j55_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j55_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,40,2484,'".AddSlashes(pg_result($resaco,$conresaco,'j55_numcgm'))."','$this->j55_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averbações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j55_codave;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averbações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j55_codave;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j55_codave;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j55_codave=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j55_codave));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,198,'$j55_codave','E')");
         $resac = db_query("insert into db_acount values($acount,40,198,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_codave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,40,199,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,40,200,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,40,201,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_regimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,40,202,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,40,2484,'','".AddSlashes(pg_result($resaco,$iresaco,'j55_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averba
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j55_codave != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j55_codave = $j55_codave ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averbações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j55_codave;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averbações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j55_codave;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j55_codave;
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
        $this->erro_sql   = "Record Vazio na Tabela:averba";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j55_codave=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averba ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = averba.j55_matric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = averba.j55_numcgm";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j55_codave!=null ){
         $sql2 .= " where averba.j55_codave = $j55_codave "; 
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
   function sql_query_file ( $j55_codave=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averba ";
     $sql2 = "";
     if($dbwhere==""){
       if($j55_codave!=null ){
         $sql2 .= " where averba.j55_codave = $j55_codave "; 
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
   function sql_query_nomeant ( $j55_codave=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averba ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = averba.j55_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j55_codave!=null ){
         $sql2 .= " where averba.j55_codave = $j55_codave "; 
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