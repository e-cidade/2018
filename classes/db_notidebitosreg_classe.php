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

//MODULO: notificacoes
//CLASSE DA ENTIDADE notidebitosreg
class cl_notidebitosreg { 
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
   var $k43_sequencial = 0; 
   var $k43_numpar = 0; 
   var $k43_numpre = 0; 
   var $k43_notifica = 0; 
   var $k43_receit = 0; 
   var $k43_vlrcor = 0; 
   var $k43_vlrjur = 0; 
   var $k43_vlrmul = 0; 
   var $k43_vlrdes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k43_sequencial = int4 = Sequencial 
                 k43_numpar = int4 = Numpar 
                 k43_numpre = int4 = Numpre 
                 k43_notifica = int4 = Notificação 
                 k43_receit = int4 = Receita 
                 k43_vlrcor = float8 = Valor Corrigido 
                 k43_vlrjur = float8 = Juros 
                 k43_vlrmul = float8 = Multa 
                 k43_vlrdes = float8 = Desconto 
                 ";
   //funcao construtor da classe 
   function cl_notidebitosreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notidebitosreg"); 
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
       $this->k43_sequencial = ($this->k43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_sequencial"]:$this->k43_sequencial);
       $this->k43_numpar = ($this->k43_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_numpar"]:$this->k43_numpar);
       $this->k43_numpre = ($this->k43_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_numpre"]:$this->k43_numpre);
       $this->k43_notifica = ($this->k43_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_notifica"]:$this->k43_notifica);
       $this->k43_receit = ($this->k43_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_receit"]:$this->k43_receit);
       $this->k43_vlrcor = ($this->k43_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_vlrcor"]:$this->k43_vlrcor);
       $this->k43_vlrjur = ($this->k43_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_vlrjur"]:$this->k43_vlrjur);
       $this->k43_vlrmul = ($this->k43_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_vlrmul"]:$this->k43_vlrmul);
       $this->k43_vlrdes = ($this->k43_vlrdes == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_vlrdes"]:$this->k43_vlrdes);
     }else{
       $this->k43_sequencial = ($this->k43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k43_sequencial"]:$this->k43_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k43_sequencial){ 
      $this->atualizacampos();
     if($this->k43_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "k43_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k43_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_notifica == null ){ 
       $this->erro_sql = " Campo Notificação nao Informado.";
       $this->erro_campo = "k43_notifica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k43_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "k43_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_vlrjur == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k43_vlrjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_vlrmul == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k43_vlrmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k43_vlrdes == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "k43_vlrdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k43_sequencial == "" || $k43_sequencial == null ){
       $result = db_query("select nextval('notidebitosreg_k43_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notidebitosreg_k43_sequencial_seq do campo: k43_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k43_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notidebitosreg_k43_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k43_sequencial)){
         $this->erro_sql = " Campo k43_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k43_sequencial = $k43_sequencial; 
       }
     }
     if(($this->k43_sequencial == null) || ($this->k43_sequencial == "") ){ 
       $this->erro_sql = " Campo k43_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notidebitosreg(
                                       k43_sequencial 
                                      ,k43_numpar 
                                      ,k43_numpre 
                                      ,k43_notifica 
                                      ,k43_receit 
                                      ,k43_vlrcor 
                                      ,k43_vlrjur 
                                      ,k43_vlrmul 
                                      ,k43_vlrdes 
                       )
                values (
                                $this->k43_sequencial 
                               ,$this->k43_numpar 
                               ,$this->k43_numpre 
                               ,$this->k43_notifica 
                               ,$this->k43_receit 
                               ,$this->k43_vlrcor 
                               ,$this->k43_vlrjur 
                               ,$this->k43_vlrmul 
                               ,$this->k43_vlrdes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Notificação de Débito ($this->k43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Notificação de Débito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Notificação de Débito ($this->k43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k43_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k43_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12081,'$this->k43_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2096,12081,'','".AddSlashes(pg_result($resaco,0,'k43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12082,'','".AddSlashes(pg_result($resaco,0,'k43_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12083,'','".AddSlashes(pg_result($resaco,0,'k43_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12084,'','".AddSlashes(pg_result($resaco,0,'k43_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12085,'','".AddSlashes(pg_result($resaco,0,'k43_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12086,'','".AddSlashes(pg_result($resaco,0,'k43_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12087,'','".AddSlashes(pg_result($resaco,0,'k43_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12088,'','".AddSlashes(pg_result($resaco,0,'k43_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2096,12089,'','".AddSlashes(pg_result($resaco,0,'k43_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k43_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update notidebitosreg set ";
     $virgula = "";
     if(trim($this->k43_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_sequencial"])){ 
       $sql  .= $virgula." k43_sequencial = $this->k43_sequencial ";
       $virgula = ",";
       if(trim($this->k43_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k43_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_numpar"])){ 
       $sql  .= $virgula." k43_numpar = $this->k43_numpar ";
       $virgula = ",";
       if(trim($this->k43_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "k43_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_numpre"])){ 
       $sql  .= $virgula." k43_numpre = $this->k43_numpre ";
       $virgula = ",";
       if(trim($this->k43_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k43_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_notifica"])){ 
       $sql  .= $virgula." k43_notifica = $this->k43_notifica ";
       $virgula = ",";
       if(trim($this->k43_notifica) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k43_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_receit"])){ 
       $sql  .= $virgula." k43_receit = $this->k43_receit ";
       $virgula = ",";
       if(trim($this->k43_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k43_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrcor"])){ 
       $sql  .= $virgula." k43_vlrcor = $this->k43_vlrcor ";
       $virgula = ",";
       if(trim($this->k43_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "k43_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrjur"])){ 
       $sql  .= $virgula." k43_vlrjur = $this->k43_vlrjur ";
       $virgula = ",";
       if(trim($this->k43_vlrjur) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k43_vlrjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrmul"])){ 
       $sql  .= $virgula." k43_vlrmul = $this->k43_vlrmul ";
       $virgula = ",";
       if(trim($this->k43_vlrmul) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k43_vlrmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k43_vlrdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrdes"])){ 
       $sql  .= $virgula." k43_vlrdes = $this->k43_vlrdes ";
       $virgula = ",";
       if(trim($this->k43_vlrdes) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "k43_vlrdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k43_sequencial!=null){
       $sql .= " k43_sequencial = $this->k43_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k43_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12081,'$this->k43_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2096,12081,'".AddSlashes(pg_result($resaco,$conresaco,'k43_sequencial'))."','$this->k43_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_numpar"]))
           $resac = db_query("insert into db_acount values($acount,2096,12082,'".AddSlashes(pg_result($resaco,$conresaco,'k43_numpar'))."','$this->k43_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_numpre"]))
           $resac = db_query("insert into db_acount values($acount,2096,12083,'".AddSlashes(pg_result($resaco,$conresaco,'k43_numpre'))."','$this->k43_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_notifica"]))
           $resac = db_query("insert into db_acount values($acount,2096,12084,'".AddSlashes(pg_result($resaco,$conresaco,'k43_notifica'))."','$this->k43_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_receit"]))
           $resac = db_query("insert into db_acount values($acount,2096,12085,'".AddSlashes(pg_result($resaco,$conresaco,'k43_receit'))."','$this->k43_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,2096,12086,'".AddSlashes(pg_result($resaco,$conresaco,'k43_vlrcor'))."','$this->k43_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,2096,12087,'".AddSlashes(pg_result($resaco,$conresaco,'k43_vlrjur'))."','$this->k43_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,2096,12088,'".AddSlashes(pg_result($resaco,$conresaco,'k43_vlrmul'))."','$this->k43_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k43_vlrdes"]))
           $resac = db_query("insert into db_acount values($acount,2096,12089,'".AddSlashes(pg_result($resaco,$conresaco,'k43_vlrdes'))."','$this->k43_vlrdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Notificação de Débito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Notificação de Débito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k43_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k43_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12081,'$k43_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2096,12081,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12082,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12083,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12084,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12085,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12086,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12087,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12088,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2096,12089,'','".AddSlashes(pg_result($resaco,$iresaco,'k43_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notidebitosreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k43_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k43_sequencial = $k43_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Notificação de Débito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Notificação de Débito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k43_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:notidebitosreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from notidebitosreg 																			";
     $sql .= "      inner join tabrec  			 on  tabrec.k02_codigo		  = notidebitosreg.k43_receit   ";
     $sql .= "      inner join notidebitos  	 on  notidebitos.k53_notifica = notidebitosreg.k43_notifica "; 
 	 $sql .= "								    and  notidebitos.k53_numpre   = notidebitosreg.k43_numpre   ";
 	 $sql .= "								    and  notidebitos.k53_numpar   = notidebitosreg.k43_numpar   ";
     $sql .= "      inner join tabrecjm          on  tabrecjm.k02_codjm 	  = tabrec.k02_codjm		    ";
     $sql .= "      inner join notificacao       on  notificacao.k50_notifica = notidebitos.k53_notifica    ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if($k43_sequencial!=null ){
         $sql2 .= " where notidebitosreg.k43_sequencial = $k43_sequencial "; 
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
   function sql_query_file ( $k43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notidebitosreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k43_sequencial!=null ){
         $sql2 .= " where notidebitosreg.k43_sequencial = $k43_sequencial "; 
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
   function sql_query_nome_parc ( $k43_notifica=null,$ordem=null,$dbwhere=""){ 
     
     $sql = "select distinct k43_notifica,";
     $sql .= " 		         k55_matric,  ";
	 $sql .= "       		 k56_inscr,   ";
     $sql .= "        		 substr(z01_nome,1,6)::integer as z01_numcgm, 	";
     $sql .= "        		 substr(z01_nome,8,40)::varchar(40) as z01_nome ";
	 $sql .= "from ( ";
	 $sql .= "select notidebitosreg.*, ";
	 $sql .= "       k55_matric, 	   ";
	 $sql .= "       k56_inscr, 	   ";
	 $sql .= "       k57_numcgm, 	   ";
	 $sql .= "       case when k55_matric is not null ";
	 $sql .= "            then (select lpad(z01_numcgm,6,0)||' '||z01_nome ";
	 $sql .= "                  from proprietario_nome ";
	 $sql .= "                  where j01_matric = k55_matric limit 1) ";
	 $sql .= "            else case when k56_inscr is not null ";
	 $sql .= "                      then (select lpad(q02_numcgm,6,0)||' '||z01_nome ";
	 $sql .= "                            from empresa ";
	 $sql .= "                            where q02_inscr = k56_inscr limit 1) ";
	 $sql .= "                 else (select lpad(z01_numcgm,6,0)||' '||z01_nome ";
	 $sql .= "                       from cgm ";
	 $sql .= "                       where z01_numcgm = k57_numcgm limit 1) ";
	 $sql .= "                 end";
	 $sql .= "        end as z01_nome ";
	 $sql .= "from notidebitosreg ";
	 $sql .= "     left  join notimatric  on k43_notifica = k55_notifica ";
	 $sql .= "     left  join notiinscr   on k43_notifica = k56_notifica ";
	 $sql .= "     left  join notinumcgm  on k43_notifica = k57_notifica) as x ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if(isset($k43_sequencial) && $k43_sequencial !=null ){
         $sql2 .= " where notidebitosreg.k43_sequencial = $k43_sequencial "; 
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