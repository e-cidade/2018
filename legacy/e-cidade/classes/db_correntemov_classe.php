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

//MODULO: caixa
//CLASSE DA ENTIDADE correntemov
class cl_correntemov { 
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
   var $k12_idmov = 0; 
   var $k12_idautent = 0; 
   var $k12_dtmov_dia = null; 
   var $k12_dtmov_mes = null; 
   var $k12_dtmov_ano = null; 
   var $k12_dtmov = null; 
   var $k12_horamov = null; 
   var $k12_valormov = 0; 
   var $k12_tipomov = 0; 
   var $k12_obsmov = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k12_idmov = int4 = Sequência 
                 k12_idautent = int4 = Autenticadora 
                 k12_dtmov = date = Data da Movimentação 
                 k12_horamov = varchar(5) = Hora da Movimentação 
                 k12_valormov = float8 = Valor da Movimentação 
                 k12_tipomov = int4 = Tipo de Movimentação 
                 k12_obsmov = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_correntemov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("correntemov"); 
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
       $this->k12_idmov = ($this->k12_idmov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_idmov"]:$this->k12_idmov);
       $this->k12_idautent = ($this->k12_idautent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_idautent"]:$this->k12_idautent);
       if($this->k12_dtmov == ""){
         $this->k12_dtmov_dia = ($this->k12_dtmov_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtmov_dia"]:$this->k12_dtmov_dia);
         $this->k12_dtmov_mes = ($this->k12_dtmov_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtmov_mes"]:$this->k12_dtmov_mes);
         $this->k12_dtmov_ano = ($this->k12_dtmov_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_dtmov_ano"]:$this->k12_dtmov_ano);
         if($this->k12_dtmov_dia != ""){
            $this->k12_dtmov = $this->k12_dtmov_ano."-".$this->k12_dtmov_mes."-".$this->k12_dtmov_dia;
         }
       }
       $this->k12_horamov = ($this->k12_horamov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_horamov"]:$this->k12_horamov);
       $this->k12_valormov = ($this->k12_valormov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_valormov"]:$this->k12_valormov);
       $this->k12_tipomov = ($this->k12_tipomov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_tipomov"]:$this->k12_tipomov);
       $this->k12_obsmov = ($this->k12_obsmov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_obsmov"]:$this->k12_obsmov);
     }else{
       $this->k12_idmov = ($this->k12_idmov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_idmov"]:$this->k12_idmov);
     }
   }
   // funcao para inclusao
   function incluir ($k12_idmov){ 
      $this->atualizacampos();
     if($this->k12_idautent == null ){ 
       $this->erro_sql = " Campo Autenticadora nao Informado.";
       $this->erro_campo = "k12_idautent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_dtmov == null ){ 
       $this->erro_sql = " Campo Data da Movimentação nao Informado.";
       $this->erro_campo = "k12_dtmov_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_horamov == null ){ 
       $this->erro_sql = " Campo Hora da Movimentação nao Informado.";
       $this->erro_campo = "k12_horamov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_valormov == null ){ 
       $this->erro_sql = " Campo Valor da Movimentação nao Informado.";
       $this->erro_campo = "k12_valormov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_tipomov == null ){ 
       $this->erro_sql = " Campo Tipo de Movimentação nao Informado.";
       $this->erro_campo = "k12_tipomov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_obsmov == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "k12_obsmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k12_idmov == "" || $k12_idmov == null ){
       $result = db_query("select nextval('correntemov_k12_idmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: correntemov_k12_idmov_seq do campo: k12_idmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k12_idmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from correntemov_k12_idmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $k12_idmov)){
         $this->erro_sql = " Campo k12_idmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k12_idmov = $k12_idmov; 
       }
     }
     if(($this->k12_idmov == null) || ($this->k12_idmov == "") ){ 
       $this->erro_sql = " Campo k12_idmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into correntemov(
                                       k12_idmov 
                                      ,k12_idautent 
                                      ,k12_dtmov 
                                      ,k12_horamov 
                                      ,k12_valormov 
                                      ,k12_tipomov 
                                      ,k12_obsmov 
                       )
                values (
                                $this->k12_idmov 
                               ,$this->k12_idautent 
                               ,".($this->k12_dtmov == "null" || $this->k12_dtmov == ""?"null":"'".$this->k12_dtmov."'")." 
                               ,'$this->k12_horamov' 
                               ,$this->k12_valormov 
                               ,$this->k12_tipomov 
                               ,'$this->k12_obsmov' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação Interna ($this->k12_idmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação Interna já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação Interna ($this->k12_idmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_idmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k12_idmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4691,'$this->k12_idmov','I')");
       $resac = db_query("insert into db_acount values($acount,620,4691,'','".AddSlashes(pg_result($resaco,0,'k12_idmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4692,'','".AddSlashes(pg_result($resaco,0,'k12_idautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4695,'','".AddSlashes(pg_result($resaco,0,'k12_dtmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4696,'','".AddSlashes(pg_result($resaco,0,'k12_horamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4697,'','".AddSlashes(pg_result($resaco,0,'k12_valormov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4698,'','".AddSlashes(pg_result($resaco,0,'k12_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,620,4699,'','".AddSlashes(pg_result($resaco,0,'k12_obsmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k12_idmov=null) { 
      $this->atualizacampos();
     $sql = " update correntemov set ";
     $virgula = "";
     if(trim($this->k12_idmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_idmov"])){ 
        if(trim($this->k12_idmov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k12_idmov"])){ 
           $this->k12_idmov = "0" ; 
        } 
       $sql  .= $virgula." k12_idmov = $this->k12_idmov ";
       $virgula = ",";
       if(trim($this->k12_idmov) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "k12_idmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_idautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_idautent"])){ 
        if(trim($this->k12_idautent)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k12_idautent"])){ 
           $this->k12_idautent = "0" ; 
        } 
       $sql  .= $virgula." k12_idautent = $this->k12_idautent ";
       $virgula = ",";
       if(trim($this->k12_idautent) == null ){ 
         $this->erro_sql = " Campo Autenticadora nao Informado.";
         $this->erro_campo = "k12_idautent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_dtmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_dtmov_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_dtmov_dia"] !="") ){ 
       $sql  .= $virgula." k12_dtmov = '$this->k12_dtmov' ";
       $virgula = ",";
       if(trim($this->k12_dtmov) == null ){ 
         $this->erro_sql = " Campo Data da Movimentação nao Informado.";
         $this->erro_campo = "k12_dtmov_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtmov_dia"])){ 
         $sql  .= $virgula." k12_dtmov = null ";
         $virgula = ",";
         if(trim($this->k12_dtmov) == null ){ 
           $this->erro_sql = " Campo Data da Movimentação nao Informado.";
           $this->erro_campo = "k12_dtmov_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_horamov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_horamov"])){ 
       $sql  .= $virgula." k12_horamov = '$this->k12_horamov' ";
       $virgula = ",";
       if(trim($this->k12_horamov) == null ){ 
         $this->erro_sql = " Campo Hora da Movimentação nao Informado.";
         $this->erro_campo = "k12_horamov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_valormov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_valormov"])){ 
        if(trim($this->k12_valormov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k12_valormov"])){ 
           $this->k12_valormov = "0" ; 
        } 
       $sql  .= $virgula." k12_valormov = $this->k12_valormov ";
       $virgula = ",";
       if(trim($this->k12_valormov) == null ){ 
         $this->erro_sql = " Campo Valor da Movimentação nao Informado.";
         $this->erro_campo = "k12_valormov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_tipomov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_tipomov"])){ 
        if(trim($this->k12_tipomov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k12_tipomov"])){ 
           $this->k12_tipomov = "0" ; 
        } 
       $sql  .= $virgula." k12_tipomov = $this->k12_tipomov ";
       $virgula = ",";
       if(trim($this->k12_tipomov) == null ){ 
         $this->erro_sql = " Campo Tipo de Movimentação nao Informado.";
         $this->erro_campo = "k12_tipomov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_obsmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_obsmov"])){ 
       $sql  .= $virgula." k12_obsmov = '$this->k12_obsmov' ";
       $virgula = ",";
       if(trim($this->k12_obsmov) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "k12_obsmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k12_idmov!=null){
       $sql .= " k12_idmov = $this->k12_idmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k12_idmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4691,'$this->k12_idmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_idmov"]))
           $resac = db_query("insert into db_acount values($acount,620,4691,'".AddSlashes(pg_result($resaco,$conresaco,'k12_idmov'))."','$this->k12_idmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_idautent"]))
           $resac = db_query("insert into db_acount values($acount,620,4692,'".AddSlashes(pg_result($resaco,$conresaco,'k12_idautent'))."','$this->k12_idautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_dtmov"]))
           $resac = db_query("insert into db_acount values($acount,620,4695,'".AddSlashes(pg_result($resaco,$conresaco,'k12_dtmov'))."','$this->k12_dtmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_horamov"]))
           $resac = db_query("insert into db_acount values($acount,620,4696,'".AddSlashes(pg_result($resaco,$conresaco,'k12_horamov'))."','$this->k12_horamov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_valormov"]))
           $resac = db_query("insert into db_acount values($acount,620,4697,'".AddSlashes(pg_result($resaco,$conresaco,'k12_valormov'))."','$this->k12_valormov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_tipomov"]))
           $resac = db_query("insert into db_acount values($acount,620,4698,'".AddSlashes(pg_result($resaco,$conresaco,'k12_tipomov'))."','$this->k12_tipomov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_obsmov"]))
           $resac = db_query("insert into db_acount values($acount,620,4699,'".AddSlashes(pg_result($resaco,$conresaco,'k12_obsmov'))."','$this->k12_obsmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação Interna nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_idmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação Interna nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_idmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_idmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k12_idmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k12_idmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4691,'$k12_idmov','E')");
         $resac = db_query("insert into db_acount values($acount,620,4691,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_idmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4692,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_idautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4695,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_dtmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4696,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_horamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4697,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_valormov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4698,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,620,4699,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_obsmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from correntemov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k12_idmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_idmov = $k12_idmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação Interna nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k12_idmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação Interna nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k12_idmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k12_idmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:correntemov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k12_idmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from correntemov ";
     $sql .= "      inner join cfautent  on  cfautent.k11_id = correntemov.k12_idautent";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_idmov!=null ){
         $sql2 .= " where correntemov.k12_idmov = $k12_idmov "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k11_instit = " . db_getsession("DB_instit");
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
   function sql_query_file ( $k12_idmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from correntemov ";
     $sql .= "      inner join cfautent  on  cfautent.k11_id = correntemov.k12_idautent";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_idmov!=null ){
         $sql2 .= " where correntemov.k12_idmov = $k12_idmov "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " k11_instit = " . db_getsession("DB_instit");
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