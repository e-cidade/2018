<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: prefeitura
//CLASSE DA ENTIDADE dbprefempresa
class cl_dbprefempresa { 
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
   var $q55_sequencial = 0; 
   var $q55_dbprefcgm = 0; 
   var $q55_usuario = 0; 
   var $q55_tipo = 0; 
   var $q55_dtinc_dia = null; 
   var $q55_dtinc_mes = null; 
   var $q55_dtinc_ano = null; 
   var $q55_dtinc = null; 
   var $q55_area = 0; 
   var $q55_funcionarios = 0; 
   var $q55_inscant = null; 
   var $q55_matric = 0; 
   var $q55_recbrutaano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q55_sequencial = int4 = Código sequencial 
                 q55_dbprefcgm = int4 = CGM 
                 q55_usuario = int4 = Usuário 
                 q55_tipo = int4 = Tipo 
                 q55_dtinc = date = Data de inclusão 
                 q55_area = float8 = Área 
                 q55_funcionarios = float8 = Quant. funcionarios 
                 q55_inscant = varchar(50) = Inscrição anterior 
                 q55_matric = int4 = Inscrição Municipal do Imóvel 
                 q55_recbrutaano = float8 = Receita Bruta Anual 
                 ";
   //funcao construtor da classe 
   function cl_dbprefempresa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dbprefempresa"); 
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
       $this->q55_sequencial = ($this->q55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_sequencial"]:$this->q55_sequencial);
       $this->q55_dbprefcgm = ($this->q55_dbprefcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_dbprefcgm"]:$this->q55_dbprefcgm);
       $this->q55_usuario = ($this->q55_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_usuario"]:$this->q55_usuario);
       $this->q55_tipo = ($this->q55_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_tipo"]:$this->q55_tipo);
       if($this->q55_dtinc == ""){
         $this->q55_dtinc_dia = ($this->q55_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_dtinc_dia"]:$this->q55_dtinc_dia);
         $this->q55_dtinc_mes = ($this->q55_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_dtinc_mes"]:$this->q55_dtinc_mes);
         $this->q55_dtinc_ano = ($this->q55_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_dtinc_ano"]:$this->q55_dtinc_ano);
         if($this->q55_dtinc_dia != ""){
            $this->q55_dtinc = $this->q55_dtinc_ano."-".$this->q55_dtinc_mes."-".$this->q55_dtinc_dia;
         }
       }
       $this->q55_area = ($this->q55_area == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_area"]:$this->q55_area);
       $this->q55_funcionarios = ($this->q55_funcionarios == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_funcionarios"]:$this->q55_funcionarios);
       $this->q55_inscant = ($this->q55_inscant == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_inscant"]:$this->q55_inscant);
       $this->q55_matric = ($this->q55_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_matric"]:$this->q55_matric);
       $this->q55_recbrutaano = ($this->q55_recbrutaano == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_recbrutaano"]:$this->q55_recbrutaano);
     }else{
       $this->q55_sequencial = ($this->q55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q55_sequencial"]:$this->q55_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q55_sequencial){ 
      $this->atualizacampos();
     if($this->q55_dbprefcgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "q55_dbprefcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q55_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "q55_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_dtinc == null ){ 
       $this->erro_sql = " Campo Data de inclusão nao Informado.";
       $this->erro_campo = "q55_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_area == null ){ 
       $this->erro_sql = " Campo Área nao Informado.";
       $this->erro_campo = "q55_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_funcionarios == null ){ 
       $this->erro_sql = " Campo Quant. funcionarios nao Informado.";
       $this->erro_campo = "q55_funcionarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q55_matric == null ){ 
       $this->q55_matric = "0";
     }
     if($this->q55_recbrutaano == null ){ 
       $this->q55_recbrutaano = "0";
     }
     if($q55_sequencial == "" || $q55_sequencial == null ){
       $result = db_query("select nextval('dbprefempresa_q55_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dbprefempresa_q55_sequencial_seq do campo: q55_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q55_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dbprefempresa_q55_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q55_sequencial)){
         $this->erro_sql = " Campo q55_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q55_sequencial = $q55_sequencial; 
       }
     }
     if(($this->q55_sequencial == null) || ($this->q55_sequencial == "") ){ 
       $this->erro_sql = " Campo q55_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dbprefempresa(
                                       q55_sequencial 
                                      ,q55_dbprefcgm 
                                      ,q55_usuario 
                                      ,q55_tipo 
                                      ,q55_dtinc 
                                      ,q55_area 
                                      ,q55_funcionarios 
                                      ,q55_inscant 
                                      ,q55_matric 
                                      ,q55_recbrutaano 
                       )
                values (
                                $this->q55_sequencial 
                               ,$this->q55_dbprefcgm 
                               ,$this->q55_usuario 
                               ,$this->q55_tipo 
                               ,".($this->q55_dtinc == "null" || $this->q55_dtinc == ""?"null":"'".$this->q55_dtinc."'")." 
                               ,$this->q55_area 
                               ,$this->q55_funcionarios 
                               ,'$this->q55_inscant' 
                               ,$this->q55_matric 
                               ,$this->q55_recbrutaano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dbprefempresa ($this->q55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "dbprefempresa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "dbprefempresa ($this->q55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q55_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q55_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10207,'$this->q55_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1761,10207,'','".AddSlashes(pg_result($resaco,0,'q55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10208,'','".AddSlashes(pg_result($resaco,0,'q55_dbprefcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10209,'','".AddSlashes(pg_result($resaco,0,'q55_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10210,'','".AddSlashes(pg_result($resaco,0,'q55_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10211,'','".AddSlashes(pg_result($resaco,0,'q55_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10220,'','".AddSlashes(pg_result($resaco,0,'q55_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10218,'','".AddSlashes(pg_result($resaco,0,'q55_funcionarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10231,'','".AddSlashes(pg_result($resaco,0,'q55_inscant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10546,'','".AddSlashes(pg_result($resaco,0,'q55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1761,10547,'','".AddSlashes(pg_result($resaco,0,'q55_recbrutaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q55_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dbprefempresa set ";
     $virgula = "";
     if(trim($this->q55_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_sequencial"])){ 
       $sql  .= $virgula." q55_sequencial = $this->q55_sequencial ";
       $virgula = ",";
       if(trim($this->q55_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "q55_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_dbprefcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_dbprefcgm"])){ 
       $sql  .= $virgula." q55_dbprefcgm = $this->q55_dbprefcgm ";
       $virgula = ",";
       if(trim($this->q55_dbprefcgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "q55_dbprefcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_usuario"])){ 
       $sql  .= $virgula." q55_usuario = $this->q55_usuario ";
       $virgula = ",";
       if(trim($this->q55_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q55_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_tipo"])){ 
       $sql  .= $virgula." q55_tipo = $this->q55_tipo ";
       $virgula = ",";
       if(trim($this->q55_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "q55_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q55_dtinc_dia"] !="") ){ 
       $sql  .= $virgula." q55_dtinc = '$this->q55_dtinc' ";
       $virgula = ",";
       if(trim($this->q55_dtinc) == null ){ 
         $this->erro_sql = " Campo Data de inclusão nao Informado.";
         $this->erro_campo = "q55_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q55_dtinc_dia"])){ 
         $sql  .= $virgula." q55_dtinc = null ";
         $virgula = ",";
         if(trim($this->q55_dtinc) == null ){ 
           $this->erro_sql = " Campo Data de inclusão nao Informado.";
           $this->erro_campo = "q55_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q55_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_area"])){ 
       $sql  .= $virgula." q55_area = $this->q55_area ";
       $virgula = ",";
       if(trim($this->q55_area) == null ){ 
         $this->erro_sql = " Campo Área nao Informado.";
         $this->erro_campo = "q55_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_funcionarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_funcionarios"])){ 
       $sql  .= $virgula." q55_funcionarios = $this->q55_funcionarios ";
       $virgula = ",";
       if(trim($this->q55_funcionarios) == null ){ 
         $this->erro_sql = " Campo Quant. funcionarios nao Informado.";
         $this->erro_campo = "q55_funcionarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q55_inscant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_inscant"])){ 
       $sql  .= $virgula." q55_inscant = '$this->q55_inscant' ";
       $virgula = ",";
     }
     if(trim($this->q55_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_matric"])){ 
        if(trim($this->q55_matric)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q55_matric"])){ 
           $this->q55_matric = "0" ; 
        } 
       $sql  .= $virgula." q55_matric = $this->q55_matric ";
       $virgula = ",";
     }
     if(trim($this->q55_recbrutaano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q55_recbrutaano"])){ 
        if(trim($this->q55_recbrutaano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q55_recbrutaano"])){ 
           $this->q55_recbrutaano = "0" ; 
        } 
       $sql  .= $virgula." q55_recbrutaano = $this->q55_recbrutaano ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q55_sequencial!=null){
       $sql .= " q55_sequencial = $this->q55_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q55_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10207,'$this->q55_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1761,10207,'".AddSlashes(pg_result($resaco,$conresaco,'q55_sequencial'))."','$this->q55_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_dbprefcgm"]))
           $resac = db_query("insert into db_acount values($acount,1761,10208,'".AddSlashes(pg_result($resaco,$conresaco,'q55_dbprefcgm'))."','$this->q55_dbprefcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1761,10209,'".AddSlashes(pg_result($resaco,$conresaco,'q55_usuario'))."','$this->q55_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1761,10210,'".AddSlashes(pg_result($resaco,$conresaco,'q55_tipo'))."','$this->q55_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_dtinc"]))
           $resac = db_query("insert into db_acount values($acount,1761,10211,'".AddSlashes(pg_result($resaco,$conresaco,'q55_dtinc'))."','$this->q55_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_area"]))
           $resac = db_query("insert into db_acount values($acount,1761,10220,'".AddSlashes(pg_result($resaco,$conresaco,'q55_area'))."','$this->q55_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_funcionarios"]))
           $resac = db_query("insert into db_acount values($acount,1761,10218,'".AddSlashes(pg_result($resaco,$conresaco,'q55_funcionarios'))."','$this->q55_funcionarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_inscant"]))
           $resac = db_query("insert into db_acount values($acount,1761,10231,'".AddSlashes(pg_result($resaco,$conresaco,'q55_inscant'))."','$this->q55_inscant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_matric"]))
           $resac = db_query("insert into db_acount values($acount,1761,10546,'".AddSlashes(pg_result($resaco,$conresaco,'q55_matric'))."','$this->q55_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q55_recbrutaano"]))
           $resac = db_query("insert into db_acount values($acount,1761,10547,'".AddSlashes(pg_result($resaco,$conresaco,'q55_recbrutaano'))."','$this->q55_recbrutaano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbprefempresa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbprefempresa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q55_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q55_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10207,'$q55_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1761,10207,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10208,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_dbprefcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10209,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10210,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10211,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10220,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10218,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_funcionarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10231,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_inscant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10546,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1761,10547,'','".AddSlashes(pg_result($resaco,$iresaco,'q55_recbrutaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from dbprefempresa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q55_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q55_sequencial = $q55_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dbprefempresa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dbprefempresa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q55_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dbprefempresa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefempresa ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = dbprefempresa.q55_usuario";
     $sql .= "      inner join issporte  on  issporte.q40_codporte = dbprefempresa.q55_issporte";
     $sql .= "      inner join dbprefcgm  on  dbprefcgm.z01_sequencial = dbprefempresa.q55_dbprefcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q55_sequencial!=null ){
         $sql2 .= " where dbprefempresa.q55_sequencial = $q55_sequencial "; 
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
   function sql_query_file ( $q55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefempresa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q55_sequencial!=null ){
         $sql2 .= " where dbprefempresa.q55_sequencial = $q55_sequencial "; 
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