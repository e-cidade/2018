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

//MODULO: Caixa
//CLASSE DA ENTIDADE cfautent
class cl_cfautent { 
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
   var $k11_id = 0; 
   var $k11_ident1 = null; 
   var $k11_ident2 = null; 
   var $k11_ident3 = null; 
   var $k11_ipterm = null; 
   var $k11_local = null; 
   var $k11_aut1 = null; 
   var $k11_aut2 = null; 
   var $k11_tipautent = 0; 
   var $k11_tesoureiro = null; 
   var $k11_instit = 0; 
   var $k11_tipoimp = 0; 
   var $k11_tipoimpcheque = 0; 
   var $k11_ipimpcheque = null; 
   var $k11_portaimpcheque = 0; 
   var $k11_impassche = 0; 
   var $k11_zeratrocoarrec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k11_id = int4 = Código do Terminal 
                 k11_ident1 = char(1) = Identificação 1 
                 k11_ident2 = char(1) = Identificação 2 
                 k11_ident3 = char(1) = Identificação 3 
                 k11_ipterm = varchar(20) = IP Terminal 
                 k11_local = char(30) = Local do Terminal 
                 k11_aut1 = varchar(20) = String para Autenticacao 1 
                 k11_aut2 = varchar(20) = String para Autenticacao 2 
                 k11_tipautent = int4 = Tipo de Autenticação 
                 k11_tesoureiro = varchar(40) = Tesoureiro 
                 k11_instit = int4 = Instituição 
                 k11_tipoimp = int4 = tipo de impressora 
                 k11_tipoimpcheque = int4 = Tipo Impressora Cheque 
                 k11_ipimpcheque = varchar(20) = IP Impressora Cheque 
                 k11_portaimpcheque = int4 = Porta Impressora Cheque 
                 k11_impassche = int4 = Imprime assinatura no cheque 
                 k11_zeratrocoarrec = int4 = Zera troco arrecadação de receita 
                 ";
   //funcao construtor da classe 
   function cl_cfautent() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfautent"); 
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
       $this->k11_id = ($this->k11_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_id"]:$this->k11_id);
       $this->k11_ident1 = ($this->k11_ident1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_ident1"]:$this->k11_ident1);
       $this->k11_ident2 = ($this->k11_ident2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_ident2"]:$this->k11_ident2);
       $this->k11_ident3 = ($this->k11_ident3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_ident3"]:$this->k11_ident3);
       $this->k11_ipterm = ($this->k11_ipterm == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_ipterm"]:$this->k11_ipterm);
       $this->k11_local = ($this->k11_local == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_local"]:$this->k11_local);
       $this->k11_aut1 = ($this->k11_aut1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_aut1"]:$this->k11_aut1);
       $this->k11_aut2 = ($this->k11_aut2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_aut2"]:$this->k11_aut2);
       $this->k11_tipautent = ($this->k11_tipautent == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_tipautent"]:$this->k11_tipautent);
       $this->k11_tesoureiro = ($this->k11_tesoureiro == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_tesoureiro"]:$this->k11_tesoureiro);
       $this->k11_instit = ($this->k11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_instit"]:$this->k11_instit);
       $this->k11_tipoimp = ($this->k11_tipoimp == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_tipoimp"]:$this->k11_tipoimp);
       $this->k11_tipoimpcheque = ($this->k11_tipoimpcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_tipoimpcheque"]:$this->k11_tipoimpcheque);
       $this->k11_ipimpcheque = ($this->k11_ipimpcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_ipimpcheque"]:$this->k11_ipimpcheque);
       $this->k11_portaimpcheque = ($this->k11_portaimpcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_portaimpcheque"]:$this->k11_portaimpcheque);
       $this->k11_impassche = ($this->k11_impassche == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_impassche"]:$this->k11_impassche);
       $this->k11_zeratrocoarrec = ($this->k11_zeratrocoarrec == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_zeratrocoarrec"]:$this->k11_zeratrocoarrec);
     }else{
       $this->k11_id = ($this->k11_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_id"]:$this->k11_id);
     }
   }
   // funcao para inclusao
   function incluir ($k11_id){ 
      $this->atualizacampos();
     if($this->k11_tipautent == null ){ 
       $this->erro_sql = " Campo Tipo de Autenticação nao Informado.";
       $this->erro_campo = "k11_tipautent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_tesoureiro == null ){ 
       $this->erro_sql = " Campo Tesoureiro nao Informado.";
       $this->erro_campo = "k11_tesoureiro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k11_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_tipoimp == null ){ 
       $this->erro_sql = " Campo tipo de impressora nao Informado.";
       $this->erro_campo = "k11_tipoimp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_tipoimpcheque == null ){ 
       $this->erro_sql = " Campo Tipo Impressora Cheque nao Informado.";
       $this->erro_campo = "k11_tipoimpcheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_ipimpcheque == null ){ 
       $this->erro_sql = " Campo IP Impressora Cheque nao Informado.";
       $this->erro_campo = "k11_ipimpcheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_portaimpcheque == null ){ 
       $this->erro_sql = " Campo Porta Impressora Cheque nao Informado.";
       $this->erro_campo = "k11_portaimpcheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_impassche == null ){ 
       $this->erro_sql = " Campo Imprime assinatura no cheque nao Informado.";
       $this->erro_campo = "k11_impassche";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_zeratrocoarrec == null ){ 
       $this->erro_sql = " Campo Zera troco arrecadação de receita nao Informado.";
       $this->erro_campo = "k11_zeratrocoarrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k11_id == "" || $k11_id == null ){
       $result = db_query("select nextval('cfautent_k11_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cfautent_k11_id_seq do campo: k11_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k11_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cfautent_k11_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $k11_id)){
         $this->erro_sql = " Campo k11_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k11_id = $k11_id; 
       }
     }
     if(($this->k11_id == null) || ($this->k11_id == "") ){ 
       $this->erro_sql = " Campo k11_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfautent(
                                       k11_id 
                                      ,k11_ident1 
                                      ,k11_ident2 
                                      ,k11_ident3 
                                      ,k11_ipterm 
                                      ,k11_local 
                                      ,k11_aut1 
                                      ,k11_aut2 
                                      ,k11_tipautent 
                                      ,k11_tesoureiro 
                                      ,k11_instit 
                                      ,k11_tipoimp 
                                      ,k11_tipoimpcheque 
                                      ,k11_ipimpcheque 
                                      ,k11_portaimpcheque 
                                      ,k11_impassche 
                                      ,k11_zeratrocoarrec 
                       )
                values (
                                $this->k11_id 
                               ,'$this->k11_ident1' 
                               ,'$this->k11_ident2' 
                               ,'$this->k11_ident3' 
                               ,'$this->k11_ipterm' 
                               ,'$this->k11_local' 
                               ,'$this->k11_aut1' 
                               ,'$this->k11_aut2' 
                               ,$this->k11_tipautent 
                               ,'$this->k11_tesoureiro' 
                               ,$this->k11_instit 
                               ,$this->k11_tipoimp 
                               ,$this->k11_tipoimpcheque 
                               ,'$this->k11_ipimpcheque' 
                               ,$this->k11_portaimpcheque 
                               ,$this->k11_impassche 
                               ,$this->k11_zeratrocoarrec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração ($this->k11_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração ($this->k11_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k11_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k11_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1131,'$this->k11_id','I')");
       $resac = db_query("insert into db_acount values($acount,199,1131,'','".AddSlashes(pg_result($resaco,0,'k11_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1132,'','".AddSlashes(pg_result($resaco,0,'k11_ident1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1133,'','".AddSlashes(pg_result($resaco,0,'k11_ident2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1134,'','".AddSlashes(pg_result($resaco,0,'k11_ident3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1135,'','".AddSlashes(pg_result($resaco,0,'k11_ipterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1136,'','".AddSlashes(pg_result($resaco,0,'k11_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1137,'','".AddSlashes(pg_result($resaco,0,'k11_aut1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,1138,'','".AddSlashes(pg_result($resaco,0,'k11_aut2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,6056,'','".AddSlashes(pg_result($resaco,0,'k11_tipautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,6206,'','".AddSlashes(pg_result($resaco,0,'k11_tesoureiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,6165,'','".AddSlashes(pg_result($resaco,0,'k11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,6846,'','".AddSlashes(pg_result($resaco,0,'k11_tipoimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,9851,'','".AddSlashes(pg_result($resaco,0,'k11_tipoimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,9852,'','".AddSlashes(pg_result($resaco,0,'k11_ipimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,9853,'','".AddSlashes(pg_result($resaco,0,'k11_portaimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,10841,'','".AddSlashes(pg_result($resaco,0,'k11_impassche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,199,12604,'','".AddSlashes(pg_result($resaco,0,'k11_zeratrocoarrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k11_id=null) { 
      $this->atualizacampos();
     $sql = " update cfautent set ";
     $virgula = "";
     if(trim($this->k11_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_id"])){ 
       $sql  .= $virgula." k11_id = $this->k11_id ";
       $virgula = ",";
       if(trim($this->k11_id) == null ){ 
         $this->erro_sql = " Campo Código do Terminal nao Informado.";
         $this->erro_campo = "k11_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_ident1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_ident1"])){ 
       $sql  .= $virgula." k11_ident1 = '$this->k11_ident1' ";
       $virgula = ",";
     }
     if(trim($this->k11_ident2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_ident2"])){ 
       $sql  .= $virgula." k11_ident2 = '$this->k11_ident2' ";
       $virgula = ",";
     }
     if(trim($this->k11_ident3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_ident3"])){ 
       $sql  .= $virgula." k11_ident3 = '$this->k11_ident3' ";
       $virgula = ",";
     }
     if(trim($this->k11_ipterm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_ipterm"])){ 
       $sql  .= $virgula." k11_ipterm = '$this->k11_ipterm' ";
       $virgula = ",";
     }
     if(trim($this->k11_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_local"])){ 
       $sql  .= $virgula." k11_local = '$this->k11_local' ";
       $virgula = ",";
     }
     if(trim($this->k11_aut1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_aut1"])){ 
       $sql  .= $virgula." k11_aut1 = '$this->k11_aut1' ";
       $virgula = ",";
     }
     if(trim($this->k11_aut2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_aut2"])){ 
       $sql  .= $virgula." k11_aut2 = '$this->k11_aut2' ";
       $virgula = ",";
     }
     if(trim($this->k11_tipautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_tipautent"])){ 
       $sql  .= $virgula." k11_tipautent = $this->k11_tipautent ";
       $virgula = ",";
       if(trim($this->k11_tipautent) == null ){ 
         $this->erro_sql = " Campo Tipo de Autenticação nao Informado.";
         $this->erro_campo = "k11_tipautent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_tesoureiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_tesoureiro"])){ 
       $sql  .= $virgula." k11_tesoureiro = '$this->k11_tesoureiro' ";
       $virgula = ",";
       if(trim($this->k11_tesoureiro) == null ){ 
         $this->erro_sql = " Campo Tesoureiro nao Informado.";
         $this->erro_campo = "k11_tesoureiro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_instit"])){ 
       $sql  .= $virgula." k11_instit = $this->k11_instit ";
       $virgula = ",";
       if(trim($this->k11_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_tipoimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_tipoimp"])){ 
       $sql  .= $virgula." k11_tipoimp = $this->k11_tipoimp ";
       $virgula = ",";
       if(trim($this->k11_tipoimp) == null ){ 
         $this->erro_sql = " Campo tipo de impressora nao Informado.";
         $this->erro_campo = "k11_tipoimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_tipoimpcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_tipoimpcheque"])){ 
       $sql  .= $virgula." k11_tipoimpcheque = $this->k11_tipoimpcheque ";
       $virgula = ",";
       if(trim($this->k11_tipoimpcheque) == null ){ 
         $this->erro_sql = " Campo Tipo Impressora Cheque nao Informado.";
         $this->erro_campo = "k11_tipoimpcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_ipimpcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_ipimpcheque"])){ 
       $sql  .= $virgula." k11_ipimpcheque = '$this->k11_ipimpcheque' ";
       $virgula = ",";
       if(trim($this->k11_ipimpcheque) == null ){ 
         $this->erro_sql = " Campo IP Impressora Cheque nao Informado.";
         $this->erro_campo = "k11_ipimpcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_portaimpcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_portaimpcheque"])){ 
       $sql  .= $virgula." k11_portaimpcheque = $this->k11_portaimpcheque ";
       $virgula = ",";
       if(trim($this->k11_portaimpcheque) == null ){ 
         $this->erro_sql = " Campo Porta Impressora Cheque nao Informado.";
         $this->erro_campo = "k11_portaimpcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_impassche)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_impassche"])){ 
       $sql  .= $virgula." k11_impassche = $this->k11_impassche ";
       $virgula = ",";
       if(trim($this->k11_impassche) == null ){ 
         $this->erro_sql = " Campo Imprime assinatura no cheque nao Informado.";
         $this->erro_campo = "k11_impassche";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_zeratrocoarrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_zeratrocoarrec"])){ 
       $sql  .= $virgula." k11_zeratrocoarrec = $this->k11_zeratrocoarrec ";
       $virgula = ",";
       if(trim($this->k11_zeratrocoarrec) == null ){ 
         $this->erro_sql = " Campo Zera troco arrecadação de receita nao Informado.";
         $this->erro_campo = "k11_zeratrocoarrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k11_id!=null){
       $sql .= " k11_id = $this->k11_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k11_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1131,'$this->k11_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_id"]))
           $resac = db_query("insert into db_acount values($acount,199,1131,'".AddSlashes(pg_result($resaco,$conresaco,'k11_id'))."','$this->k11_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_ident1"]))
           $resac = db_query("insert into db_acount values($acount,199,1132,'".AddSlashes(pg_result($resaco,$conresaco,'k11_ident1'))."','$this->k11_ident1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_ident2"]))
           $resac = db_query("insert into db_acount values($acount,199,1133,'".AddSlashes(pg_result($resaco,$conresaco,'k11_ident2'))."','$this->k11_ident2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_ident3"]))
           $resac = db_query("insert into db_acount values($acount,199,1134,'".AddSlashes(pg_result($resaco,$conresaco,'k11_ident3'))."','$this->k11_ident3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_ipterm"]))
           $resac = db_query("insert into db_acount values($acount,199,1135,'".AddSlashes(pg_result($resaco,$conresaco,'k11_ipterm'))."','$this->k11_ipterm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_local"]))
           $resac = db_query("insert into db_acount values($acount,199,1136,'".AddSlashes(pg_result($resaco,$conresaco,'k11_local'))."','$this->k11_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_aut1"]))
           $resac = db_query("insert into db_acount values($acount,199,1137,'".AddSlashes(pg_result($resaco,$conresaco,'k11_aut1'))."','$this->k11_aut1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_aut2"]))
           $resac = db_query("insert into db_acount values($acount,199,1138,'".AddSlashes(pg_result($resaco,$conresaco,'k11_aut2'))."','$this->k11_aut2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_tipautent"]))
           $resac = db_query("insert into db_acount values($acount,199,6056,'".AddSlashes(pg_result($resaco,$conresaco,'k11_tipautent'))."','$this->k11_tipautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_tesoureiro"]))
           $resac = db_query("insert into db_acount values($acount,199,6206,'".AddSlashes(pg_result($resaco,$conresaco,'k11_tesoureiro'))."','$this->k11_tesoureiro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_instit"]))
           $resac = db_query("insert into db_acount values($acount,199,6165,'".AddSlashes(pg_result($resaco,$conresaco,'k11_instit'))."','$this->k11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_tipoimp"]))
           $resac = db_query("insert into db_acount values($acount,199,6846,'".AddSlashes(pg_result($resaco,$conresaco,'k11_tipoimp'))."','$this->k11_tipoimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_tipoimpcheque"]))
           $resac = db_query("insert into db_acount values($acount,199,9851,'".AddSlashes(pg_result($resaco,$conresaco,'k11_tipoimpcheque'))."','$this->k11_tipoimpcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_ipimpcheque"]))
           $resac = db_query("insert into db_acount values($acount,199,9852,'".AddSlashes(pg_result($resaco,$conresaco,'k11_ipimpcheque'))."','$this->k11_ipimpcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_portaimpcheque"]))
           $resac = db_query("insert into db_acount values($acount,199,9853,'".AddSlashes(pg_result($resaco,$conresaco,'k11_portaimpcheque'))."','$this->k11_portaimpcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_impassche"]))
           $resac = db_query("insert into db_acount values($acount,199,10841,'".AddSlashes(pg_result($resaco,$conresaco,'k11_impassche'))."','$this->k11_impassche',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_zeratrocoarrec"]))
           $resac = db_query("insert into db_acount values($acount,199,12604,'".AddSlashes(pg_result($resaco,$conresaco,'k11_zeratrocoarrec'))."','$this->k11_zeratrocoarrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k11_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k11_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k11_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k11_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k11_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1131,'$k11_id','E')");
         $resac = db_query("insert into db_acount values($acount,199,1131,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1132,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_ident1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1133,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_ident2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1134,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_ident3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1135,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_ipterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1136,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1137,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_aut1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,1138,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_aut2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,6056,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_tipautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,6206,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_tesoureiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,6165,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,6846,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_tipoimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,9851,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_tipoimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,9852,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_ipimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,9853,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_portaimpcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,10841,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_impassche'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,199,12604,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_zeratrocoarrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cfautent
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k11_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k11_id = $k11_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k11_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k11_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k11_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:cfautent";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k11_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfautent ";
     $sql .= "      inner join db_config  on  db_config.codigo = cfautent.k11_instit";
     $sql .= "      inner join db_impressora  on  db_impressora.db64_sequencial = cfautent.k11_tipoimp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoimpressora  on  db_tipoimpressora.db65_sequencial = db_impressora.db64_db_tipoimpressora";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_id!=null ){
         $sql2 .= " where cfautent.k11_id = $k11_id "; 
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
   function sql_query_file ( $k11_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfautent ";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_id!=null ){
         $sql2 .= " where cfautent.k11_id = $k11_id "; 
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
  
  function sql_query_impressora( $k11_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfautent ";
     $sql .= "      inner join db_config  			 on  db_config.codigo  = cfautent.k11_instit";
     $sql .= "      inner join db_impressora 	a  on  a.db64_sequencial = cfautent.k11_tipoimp";
     $sql .= "      inner join db_impressora 	b  on  b.db64_sequencial = cfautent.k11_tipoimpcheque";
     $sql .= "      inner join cgm  						 on  cgm.z01_numcgm  	 = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_id!=null ){
         $sql2 .= " where cfautent.k11_id = $k11_id "; 
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
  
  function sql_query_impressora_modelo_impressao( $k11_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfautent ";
     $sql .= "      inner join db_config               on  db_config.codigo    = cfautent.k11_instit";
     $sql .= "      inner join db_impressora  a        on  a.db64_sequencial   = cfautent.k11_tipoimp";
     $sql .= "      inner join db_impressora  b        on  b.db64_sequencial   = cfautent.k11_tipoimpcheque";
     $sql .= "      inner join cgm                     on  cgm.z01_numcgm      = db_config.numcgm";
     $sql .= "      left  join cfautentmodeloimpressao on db68_cfautent        = k11_id ";
     $sql .= "      left  join db_modeloimpressao      on db68_modeloimpressao = db66_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_id!=null ){
         $sql2 .= " where cfautent.k11_id = $k11_id "; 
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