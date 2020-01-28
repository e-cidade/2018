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

//MODULO: issqn
//CLASSE DA ENTIDADE issnotaavulsa
class cl_issnotaavulsa { 
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
   var $q51_sequencial = 0; 
   var $q51_numnota = 0; 
   var $q51_inscr = 0; 
   var $q51_usuario = 0; 
   var $q51_dtemiss_dia = null; 
   var $q51_dtemiss_mes = null; 
   var $q51_dtemiss_ano = null; 
   var $q51_dtemiss = null; 
   var $q51_hora = null; 
   var $q51_data_dia = null; 
   var $q51_data_mes = null; 
   var $q51_data_ano = null; 
   var $q51_data = null; 
   var $q51_codautent = null; 
   var $q51_pdfnota = 0; 
   var $q51_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q51_sequencial = int4 = Código Sequencial 
                 q51_numnota = int4 = Número da Nota 
                 q51_inscr = int4 = Inscrição Municipal 
                 q51_usuario = int4 = Usuário 
                 q51_dtemiss = date = Data de Emissão 
                 q51_hora = char(5) = Hora Inclusão 
                 q51_data = date = Data de Inclusão 
                 q51_codautent = varchar(100) = Código de Autenticidade 
                 q51_pdfnota = oid = Arquivo da Nota Fiscal 
                 q51_obs = varchar(200) = Observações 
                 ";
   //funcao construtor da classe 
   function cl_issnotaavulsa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issnotaavulsa"); 
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
       $this->q51_sequencial = ($this->q51_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_sequencial"]:$this->q51_sequencial);
       $this->q51_numnota = ($this->q51_numnota == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_numnota"]:$this->q51_numnota);
       $this->q51_inscr = ($this->q51_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_inscr"]:$this->q51_inscr);
       $this->q51_usuario = ($this->q51_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_usuario"]:$this->q51_usuario);
       if($this->q51_dtemiss == ""){
         $this->q51_dtemiss_dia = ($this->q51_dtemiss_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_dia"]:$this->q51_dtemiss_dia);
         $this->q51_dtemiss_mes = ($this->q51_dtemiss_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_mes"]:$this->q51_dtemiss_mes);
         $this->q51_dtemiss_ano = ($this->q51_dtemiss_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_ano"]:$this->q51_dtemiss_ano);
         if($this->q51_dtemiss_dia != ""){
            $this->q51_dtemiss = $this->q51_dtemiss_ano."-".$this->q51_dtemiss_mes."-".$this->q51_dtemiss_dia;
         }
       }
       $this->q51_hora = ($this->q51_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_hora"]:$this->q51_hora);
       if($this->q51_data == ""){
         $this->q51_data_dia = ($this->q51_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_data_dia"]:$this->q51_data_dia);
         $this->q51_data_mes = ($this->q51_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_data_mes"]:$this->q51_data_mes);
         $this->q51_data_ano = ($this->q51_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_data_ano"]:$this->q51_data_ano);
         if($this->q51_data_dia != ""){
            $this->q51_data = $this->q51_data_ano."-".$this->q51_data_mes."-".$this->q51_data_dia;
         }
       }
       $this->q51_codautent = ($this->q51_codautent == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_codautent"]:$this->q51_codautent);
       $this->q51_pdfnota = ($this->q51_pdfnota == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_pdfnota"]:$this->q51_pdfnota);
       $this->q51_obs = ($this->q51_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_obs"]:$this->q51_obs);
     }else{
       $this->q51_sequencial = ($this->q51_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q51_sequencial"]:$this->q51_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q51_sequencial){ 
      $this->atualizacampos();
     if($this->q51_numnota == null ){ 
       $this->erro_sql = " Campo Número da Nota nao Informado.";
       $this->erro_campo = "q51_numnota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
       $this->erro_campo = "q51_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q51_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_dtemiss == null ){ 
       $this->erro_sql = " Campo Data de Emissão nao Informado.";
       $this->erro_campo = "q51_dtemiss_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_hora == null ){ 
       $this->erro_sql = " Campo Hora Inclusão nao Informado.";
       $this->erro_campo = "q51_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_data == null ){ 
       $this->erro_sql = " Campo Data de Inclusão nao Informado.";
       $this->erro_campo = "q51_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q51_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "q51_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q51_sequencial == "" || $q51_sequencial == null ){
       $result = db_query("select nextval('issnotaavulsa_q51_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issnotaavulsa_q51_sequencial_seq do campo: q51_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q51_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issnotaavulsa_q51_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q51_sequencial)){
         $this->erro_sql = " Campo q51_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q51_sequencial = $q51_sequencial; 
       }
     }
     if(($this->q51_sequencial == null) || ($this->q51_sequencial == "") ){ 
       $this->erro_sql = " Campo q51_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issnotaavulsa(
                                       q51_sequencial 
                                      ,q51_numnota 
                                      ,q51_inscr 
                                      ,q51_usuario 
                                      ,q51_dtemiss 
                                      ,q51_hora 
                                      ,q51_data 
                                      ,q51_codautent 
                                      ,q51_pdfnota 
                                      ,q51_obs 
                       )
                values (
                                $this->q51_sequencial 
                               ,$this->q51_numnota 
                               ,$this->q51_inscr 
                               ,$this->q51_usuario 
                               ,".($this->q51_dtemiss == "null" || $this->q51_dtemiss == ""?"null":"'".$this->q51_dtemiss."'")." 
                               ,'$this->q51_hora' 
                               ,".($this->q51_data == "null" || $this->q51_data == ""?"null":"'".$this->q51_data."'")." 
                               ,'$this->q51_codautent' 
                               ,$this->q51_pdfnota 
                               ,'$this->q51_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Notas Avulsas ($this->q51_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Notas Avulsas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Notas Avulsas ($this->q51_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q51_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q51_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10624,'$this->q51_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1826,10624,'','".AddSlashes(pg_result($resaco,0,'q51_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10578,'','".AddSlashes(pg_result($resaco,0,'q51_numnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10626,'','".AddSlashes(pg_result($resaco,0,'q51_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10580,'','".AddSlashes(pg_result($resaco,0,'q51_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10579,'','".AddSlashes(pg_result($resaco,0,'q51_dtemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10581,'','".AddSlashes(pg_result($resaco,0,'q51_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10582,'','".AddSlashes(pg_result($resaco,0,'q51_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10625,'','".AddSlashes(pg_result($resaco,0,'q51_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,10630,'','".AddSlashes(pg_result($resaco,0,'q51_pdfnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1826,11315,'','".AddSlashes(pg_result($resaco,0,'q51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q51_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issnotaavulsa set ";
     $virgula = "";
     if(trim($this->q51_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_sequencial"])){ 
       $sql  .= $virgula." q51_sequencial = $this->q51_sequencial ";
       $virgula = ",";
       if(trim($this->q51_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q51_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q51_numnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_numnota"])){ 
       $sql  .= $virgula." q51_numnota = $this->q51_numnota ";
       $virgula = ",";
       if(trim($this->q51_numnota) == null ){ 
         $this->erro_sql = " Campo Número da Nota nao Informado.";
         $this->erro_campo = "q51_numnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q51_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_inscr"])){ 
       $sql  .= $virgula." q51_inscr = $this->q51_inscr ";
       $virgula = ",";
       if(trim($this->q51_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
         $this->erro_campo = "q51_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q51_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_usuario"])){ 
       $sql  .= $virgula." q51_usuario = $this->q51_usuario ";
       $virgula = ",";
       if(trim($this->q51_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q51_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q51_dtemiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_dia"] !="") ){ 
       $sql  .= $virgula." q51_dtemiss = '$this->q51_dtemiss' ";
       $virgula = ",";
       if(trim($this->q51_dtemiss) == null ){ 
         $this->erro_sql = " Campo Data de Emissão nao Informado.";
         $this->erro_campo = "q51_dtemiss_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q51_dtemiss_dia"])){ 
         $sql  .= $virgula." q51_dtemiss = null ";
         $virgula = ",";
         if(trim($this->q51_dtemiss) == null ){ 
           $this->erro_sql = " Campo Data de Emissão nao Informado.";
           $this->erro_campo = "q51_dtemiss_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q51_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_hora"])){ 
       $sql  .= $virgula." q51_hora = '$this->q51_hora' ";
       $virgula = ",";
       if(trim($this->q51_hora) == null ){ 
         $this->erro_sql = " Campo Hora Inclusão nao Informado.";
         $this->erro_campo = "q51_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q51_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q51_data_dia"] !="") ){ 
       $sql  .= $virgula." q51_data = '$this->q51_data' ";
       $virgula = ",";
       if(trim($this->q51_data) == null ){ 
         $this->erro_sql = " Campo Data de Inclusão nao Informado.";
         $this->erro_campo = "q51_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q51_data_dia"])){ 
         $sql  .= $virgula." q51_data = null ";
         $virgula = ",";
         if(trim($this->q51_data) == null ){ 
           $this->erro_sql = " Campo Data de Inclusão nao Informado.";
           $this->erro_campo = "q51_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q51_codautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_codautent"])){ 
       $sql  .= $virgula." q51_codautent = '$this->q51_codautent' ";
       $virgula = ",";
     }
     if(trim($this->q51_pdfnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_pdfnota"])){ 
       $sql  .= $virgula." q51_pdfnota = $this->q51_pdfnota ";
       $virgula = ",";
     }
     if(trim($this->q51_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q51_obs"])){ 
       $sql  .= $virgula." q51_obs = '$this->q51_obs' ";
       $virgula = ",";
       if(trim($this->q51_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "q51_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q51_sequencial!=null){
       $sql .= " q51_sequencial = $this->q51_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q51_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10624,'$this->q51_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1826,10624,'".AddSlashes(pg_result($resaco,$conresaco,'q51_sequencial'))."','$this->q51_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_numnota"]))
           $resac = db_query("insert into db_acount values($acount,1826,10578,'".AddSlashes(pg_result($resaco,$conresaco,'q51_numnota'))."','$this->q51_numnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_inscr"]))
           $resac = db_query("insert into db_acount values($acount,1826,10626,'".AddSlashes(pg_result($resaco,$conresaco,'q51_inscr'))."','$this->q51_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1826,10580,'".AddSlashes(pg_result($resaco,$conresaco,'q51_usuario'))."','$this->q51_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_dtemiss"]))
           $resac = db_query("insert into db_acount values($acount,1826,10579,'".AddSlashes(pg_result($resaco,$conresaco,'q51_dtemiss'))."','$this->q51_dtemiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_hora"]))
           $resac = db_query("insert into db_acount values($acount,1826,10581,'".AddSlashes(pg_result($resaco,$conresaco,'q51_hora'))."','$this->q51_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_data"]))
           $resac = db_query("insert into db_acount values($acount,1826,10582,'".AddSlashes(pg_result($resaco,$conresaco,'q51_data'))."','$this->q51_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_codautent"]))
           $resac = db_query("insert into db_acount values($acount,1826,10625,'".AddSlashes(pg_result($resaco,$conresaco,'q51_codautent'))."','$this->q51_codautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_pdfnota"]))
           $resac = db_query("insert into db_acount values($acount,1826,10630,'".AddSlashes(pg_result($resaco,$conresaco,'q51_pdfnota'))."','$this->q51_pdfnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q51_obs"]))
           $resac = db_query("insert into db_acount values($acount,1826,11315,'".AddSlashes(pg_result($resaco,$conresaco,'q51_obs'))."','$this->q51_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Notas Avulsas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q51_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Notas Avulsas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q51_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q51_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10624,'$q51_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1826,10624,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10578,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_numnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10626,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10580,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10579,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_dtemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10581,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10582,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10625,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,10630,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_pdfnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1826,11315,'','".AddSlashes(pg_result($resaco,$iresaco,'q51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issnotaavulsa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q51_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q51_sequencial = $q51_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Notas Avulsas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q51_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Notas Avulsas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q51_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q51_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issnotaavulsa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function emiteNotaAvulsa($q51_sequencial){
     if (!class_exists("cl_issnotaavulsaemissao")){
        require "classes/db_issnotaavulsaemissao_classe.php";
     }
     $clissnotaavulsaemissao = new cl_issnotaavulsaemissao();
     (bool)$lCommit = true;
     $SQLjaEmitido   = "select q69_issnotaavulsa";
     $SQLjaEmitido  .= "  from issnotaavulsaemissao ";
     $SQLjaEmitido  .= " where q69_issnotaavulsa = {$q51_sequencial}"; 
     $rsEmitido      = $this->sql_record($SQLjaEmitido);
     if ($this->numrows == 0){
       
        db_inicio_transacao();
        $clissnotaavulsaemissao->q69_issnotaavulsa = $q51_sequencial;
        $clissnotaavulsaemissao->q69_usuario       = db_getsession("DB_id_usuario");
        $clissnotaavulsaemissao->q69_data          = date("Y-m-d",db_getsession("DB_datausu"));
        $clissnotaavulsaemissao->q69_hora          = date("H:i");
        $clissnotaavulsaemissao->incluir(null);
        if ($clissnotaavulsaemissao->erro_status == 0){
       
           $lCommit = false;
           $this->erro_msg = $clissnotaavulsaemissao->erro_msg;
           db_msgbox($clissnotaavulsaemissao->erro_msg);
            db_fim_transacao(true);    
        }
     }
     echo "<script>\n";
     echo "window.open('iss2_issnotaavulsanotafiscal002.php?q51_sequencial=$q51_sequencial','','location=0')\n";
     echo "</script>\n";
     db_fim_transacao(false);
     return $lCommit;

  }
   function emiteRecibo($q51_sequencial){


      $lSqlErro  = false;
      //carregando classes
      if (class_exists("cl_parissqn")){

        $clparissqn = new cl_parissqn;
      }else{
         require ("classes/db_parissqn_classe.php");
         $clparissqn = new cl_parissqn;
      }
      if (class_exists("cl_issnotaavulsaservico")){

        $clissnotaavulsaservico = new cl_issnotaavulsaservico;
      }else{
         require ("classes/db_issnotaavulsaservico_classe.php");
         $clissnotaavulsaservico = new cl_issnotaavulsaservico;
      }
      if (class_exists("cl_issnotaavulsatomador")){

        $clissnotaavulsatomador = new cl_issnotaavulsatomador;
      }else{
         require ("classes/classes/db_issnotaavulsastomador_classe.php");
         $clissnotaavulsatomador = new cl_issnotaavulsatomador;
      }
      $rsPar     = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
      $oPar      = db_utils::fieldsMemory($rsPar,0);
      $rsNot     = $this->sql_record($this->sql_query($q51_sequencial,"*"));
      $oNot      = db_utils::fieldsMemory($rsNot,0);
      $valorNota = $this->issqnAPagar($q51_sequencial);
      if ($valorNota >= $oPar->q60_notaavulsavlrmin){

          db_inicio_transacao();
         $clarrecad  = new cl_arrecad();
          $clarrehist = new cl_arrehist();
          $rsNum      = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
          $oNum       = db_utils::fieldsMemory($rsNum,0);
          //Codigo numpre do Recibo
          $rsNumnov   = db_query("select nextval('numpref_k03_numpre_seq') as k03_numnov");
          $oNumnov    = db_utils::fieldsMemory($rsNumnov,0);
          $aDataPgto  = explode("-",$oNot->q51_dtemiss);
          $dataPagto  = date("Y-m-d",mktime(0,0,0,$aDataPgto[1],$aDataPgto[2]+$oPar->q60_notaavulsadiasprazo,$aDataPgto[0]));
          $clarrecad->k00_numpre = $oNum->k03_numpre;
          $clarrecad->k00_numpar = 1;
          $clarrecad->k00_numcgm = $oNot->q02_numcgm;
          $clarrecad->k00_valor  = $valorNota;
          $clarrecad->k00_receit = $oPar->q60_receit;
          $clarrecad->k00_tipo   = $oPar->q60_tipo;
          $clarrecad->k00_dtoper = $oNot->q51_dtemiss;
          $clarrecad->k00_dtvenc = $dataPagto;
          $clarrecad->k00_numtot = 1;
          $clarrecad->k00_numdig = 1;
          $clarrecad->k00_tipojm = 1;
          $clarrecad->k00_hist   = $oPar->q60_histsemmov;
          $clarrecad->incluir();
          if ($clarrecad->erro_status == 0){

              $lSqlErro = true;
              $erro_msg = $clarrecad->erro_msg;
          }
          if (!$lSqlErro){

               $clarrehist->k00_numpre     = $oNum->k03_numpre;
               $clarrehist->k00_numpar     = 1;
               $clarrehist->k00_hist       = $oPar->q60_histsemmov;
               $clarrehist->k00_dtoper     = $oNot->q51_dtemiss;
               $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
               $clarrehist->k00_hora       = date("h:i");
              $clarrehist->k00_histtxt    = "Valor referente a nota fiscal avulsa nº ".$oNot->q51_numnota." de (".db_formatar($oNot->q51_dtemiss,"d").")";
               $clarrehist->k00_limithist  = null;
               $clarrehist->incluir(null);
               if ($clarrehist->erro_status == 0){

                  $lSqlErro = true;
                  $erro_msg = $clarrehist->erro_msg;
							
							}

          }
          if (!$lSqlErro){

            $clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
            $clissnotaavulsanumpre->q52_issnotaavulsa = $q51_sequencial;
            $clissnotaavulsanumpre->q52_numpre        = $oNum->k03_numpre;
            $clissnotaavulsanumpre->q52_numnov        = $oNumnov->k03_numnov;
            $clissnotaavulsanumpre->incluir(null);
            if ($clissnotaavulsanumpre->erro_status == 0){

                 $lSqlErro = true;
                 $erro_msg = $clissnotaavulsanumpre->erro_msg;

            }
            if (!$lSqlErro){

               $clarreinscr             = new cl_arreinscr();
               $clarreinscr->k00_perc   = 100;
               $clarreinscr->k00_inscr  = $oNot->q02_inscr;
               $clarreinscr->k00_numpre = $oNum->k03_numpre;
               $clarreinscr->incluir($oNum->k03_numpre,$oNot->q02_inscr);
               if ($clarreinscr->erro_status == 0){

                    $lSqlErro = true;
                    $erro_msg = $clarreinscr->erro_msg;
               }
            }

          }

          db_fim_transacao($lSqlErro);
          if ($lSqlErro){

            db_msgbox($erro_msg);
          }else{

           $db_botao = false;
           $rsObs    = $clissnotaavulsaservico->sql_record(
                               $clissnotaavulsaservico->sql_query(null,"sum(q62_vlrissqn) as tvlrissqn,
                                                                         sum(q62_vlrdeducao) as tvlrdeducoes,
                                                                         sum(q62_vlrtotal) as tvlrtotal",
                                                                  null,"q62_issnotaavulsa=".$q51_sequencial));
           $rsTom = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($q51_sequencial));
           $oTom  = db_utils::fieldsMemory($rsTom,0);
           $oObs  = db_utils::fieldsmemory($rsObs,0);
           $obs   = "Referente a nota fiscal avulsa nº ".$oNot->q51_numnota."\n";
           $obs  .= "Tomador : ".$oTom->z01_cgccpf." - ".$oTom->z01_nome."\n";
           $obs  .= "Imposto : R$ ".trim(db_formatar($oObs->tvlrissqn,"f"))."\n";
           $obs  .= "Deduções: R$ ".trim(db_formatar($oObs->tvlrdeducoes,"f"))."\n";
           $obs  .= "Valor serviço: R$ ".trim(db_formatar($oObs->tvlrtotal,"f"))."\n";
           session_register("DB_obsrecibo",$obs);
           db_putsession("DB_obsrecibo",$obs);
           $url   = "iss1_issnotaavulsarecibo.php?numpre=".$oNum->k03_numpre."&tipo=".$oPar->q60_tipo."&ver_inscr=".$oNot->q02_inscr;
           $url  .= "&numcgm=".$oNot->q02_numcgm."&emrec=t&CHECK10=".$oNum->k03_numpre."P1&tipo_debito=".$oPar->q60_tipo;
           $url  .= "&k03_tipo=".$oPar->q60_tipo."&k03_parcelamento=f&k03_perparc=f&ver_numcgm=".$oNot->q02_numcgm;
           $url  .= "&totregistros=1&k03_numnov=".$oNumnov->k03_numnov."&loteador=";
           echo "<script>\n";

           echo " window.open('$url','','location=0');\n";
           echo "</script>\n";

          }

      }
      if ($lSqlErro){
        return false;
      }else{
        return true;
      }
  }
   function issqnAPagar($q51_sequencial){

      $sql  = "select sum(q62_vlrissqn) as totalissqn";
      $sql .= " from issnotaavulsaservico
                  where q62_issnotaavulsa = ".$q51_sequencial;
      $oTotal = db_utils::fieldsMemory(db_query($sql),0);
      $totalissqn = $oTotal->totalissqn;
      return $totalissqn;
  }
   function sql_query ( $q51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsa ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q51_sequencial!=null ){
         $sql2 .= " where issnotaavulsa.q51_sequencial = $q51_sequencial "; 
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
   function sql_query_baixa ( $q51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issnotaavulsa ";
     $sql .= "      inner join issbase               on  issbase.q02_inscr      = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios           on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql .= "      inner join cgm                   on  cgm.z01_numcgm         = issbase.q02_numcgm";
     $sql .= "      left  join issnotaavulsacanc     on  q63_issnotaavulsa      = q51_sequencial";
     $sql .= "      left  join issnotaavulsaemissao  on  q69_issnotaavulsa      = q51_sequencial";
     $sql .= "      left  join issnotaavulsanumpre   on  q52_issnotaavulsa      = q51_sequencial";
     $sql .= "      left  join arrecad               on  k00_numpre             = q52_numpre";
     $sql .= "                                      and  k00_numpar             = 1";
     $sql2 = "";
     if($dbwhere==""){
       if($q51_sequencial!=null ){
         $sql2 .= " where issnotaavulsa.q51_sequencial = $q51_sequencial "; 
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
   function sql_query_cancelados ( $q51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issnotaavulsa ";
     $sql .= "      inner join issbase              on  issbase.q02_inscr      = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios          on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql .= "      inner join cgm                  on  cgm.z01_numcgm         = issbase.q02_numcgm";
     $sql .= "      left  join issnotaavulsacanc    on  q63_issnotaavulsa      = q51_sequencial";
     $sql .= "      inner join issnotaavulsanumpre on  q52_issnotaavulsa      = q51_sequencial";
     $sql .= "      inner join arrecad              on  q52_numpre             = k00_numpre";
     $sql2 = "";
     if($dbwhere==""){
       if($q51_sequencial!=null ){
         $sql2 .= " where issnotaavulsa.q51_sequencial = $q51_sequencial ";
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
   function sql_query_emitidos ( $q51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issnotaavulsa ";
     $sql .= "      inner join issbase              on  issbase.q02_inscr      = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios          on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql .= "      inner join cgm                  on  cgm.z01_numcgm         = issbase.q02_numcgm";
     $sql .= "      left  join issnotaavulsacanc    on  q63_issnotaavulsa      = q51_sequencial";
     $sql .= "      left  join issnotaavulsaemissao on  q69_issnotaavulsa      = q51_sequencial";
     $sql .= "      left  join issnotaavulsanumpre  on  q52_issnotaavulsa      = q51_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($q51_sequencial!=null ){
         $sql2 .= " where issnotaavulsa.q51_sequencial = $q51_sequencial ";
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
   function sql_query_file ( $q51_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q51_sequencial!=null ){
         $sql2 .= " where issnotaavulsa.q51_sequencial = $q51_sequencial "; 
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