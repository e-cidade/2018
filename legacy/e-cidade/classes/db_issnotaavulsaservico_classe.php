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

//MODULO: issqn
//CLASSE DA ENTIDADE issnotaavulsaservico
class cl_issnotaavulsaservico { 
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
   var $q62_sequencial = 0; 
   var $q62_issnotaavulsa = 0; 
   var $q62_qtd = 0; 
   var $q62_discriminacao = null; 
   var $q62_vlruni = 0; 
   var $q62_aliquota = 0; 
   var $q62_vlrdeducao = 0; 
   var $q62_vlrtotal = 0; 
   var $q62_vlrbasecalc = 0; 
   var $q62_vlrissqn = 0; 
   var $q62_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q62_sequencial = int4 = Código Sequencial 
                 q62_issnotaavulsa = int4 = Número da Nota 
                 q62_qtd = float4 = Quantidade 
                 q62_discriminacao = text = Discriminação do Serviço 
                 q62_vlruni = float4 = Valor Unitário 
                 q62_aliquota = float4 = Aliquota 
                 q62_vlrdeducao = float4 = Deduções 
                 q62_vlrtotal = float4 = Valor Total 
                 q62_vlrbasecalc = float4 = Base de Cálculo 
                 q62_vlrissqn = float4 = Valor ISSQN 
                 q62_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_issnotaavulsaservico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issnotaavulsaservico"); 
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
       $this->q62_sequencial = ($this->q62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_sequencial"]:$this->q62_sequencial);
       $this->q62_issnotaavulsa = ($this->q62_issnotaavulsa == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_issnotaavulsa"]:$this->q62_issnotaavulsa);
       $this->q62_qtd = ($this->q62_qtd == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_qtd"]:$this->q62_qtd);
       $this->q62_discriminacao = ($this->q62_discriminacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_discriminacao"]:$this->q62_discriminacao);
       $this->q62_vlruni = ($this->q62_vlruni == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_vlruni"]:$this->q62_vlruni);
       $this->q62_aliquota = ($this->q62_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_aliquota"]:$this->q62_aliquota);
       $this->q62_vlrdeducao = ($this->q62_vlrdeducao == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_vlrdeducao"]:$this->q62_vlrdeducao);
       $this->q62_vlrtotal = ($this->q62_vlrtotal == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_vlrtotal"]:$this->q62_vlrtotal);
       $this->q62_vlrbasecalc = ($this->q62_vlrbasecalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_vlrbasecalc"]:$this->q62_vlrbasecalc);
       $this->q62_vlrissqn = ($this->q62_vlrissqn == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_vlrissqn"]:$this->q62_vlrissqn);
       $this->q62_obs = ($this->q62_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_obs"]:$this->q62_obs);
     }else{
       $this->q62_sequencial = ($this->q62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q62_sequencial"]:$this->q62_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q62_sequencial){ 
      $this->atualizacampos();
     if($this->q62_issnotaavulsa == null ){ 
       $this->erro_sql = " Campo Número da Nota nao Informado.";
       $this->erro_campo = "q62_issnotaavulsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_qtd == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "q62_qtd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_discriminacao == null ){ 
       $this->erro_sql = " Campo Discriminação do Serviço nao Informado.";
       $this->erro_campo = "q62_discriminacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_vlruni == null ){ 
       $this->erro_sql = " Campo Valor Unitário nao Informado.";
       $this->erro_campo = "q62_vlruni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "q62_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_vlrdeducao == null ){ 
       $this->q62_vlrdeducao = "0";
     }
     if($this->q62_vlrtotal == null ){ 
       $this->erro_sql = " Campo Valor Total nao Informado.";
       $this->erro_campo = "q62_vlrtotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q62_vlrbasecalc == null ){ 
       $this->q62_vlrbasecalc = "0";
     }
     if($this->q62_vlrissqn == null ){ 
       $this->q62_vlrissqn = "0";
     }
     if($q62_sequencial == "" || $q62_sequencial == null ){
       $result = db_query("select nextval('issnotaavulsaservico_q62_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issnotaavulsaservico_q62_sequencial_seq do campo: q62_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q62_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issnotaavulsaservico_q62_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q62_sequencial)){
         $this->erro_sql = " Campo q62_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q62_sequencial = $q62_sequencial; 
       }
     }
     if(($this->q62_sequencial == null) || ($this->q62_sequencial == "") ){ 
       $this->erro_sql = " Campo q62_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issnotaavulsaservico(
                                       q62_sequencial 
                                      ,q62_issnotaavulsa 
                                      ,q62_qtd 
                                      ,q62_discriminacao 
                                      ,q62_vlruni 
                                      ,q62_aliquota 
                                      ,q62_vlrdeducao 
                                      ,q62_vlrtotal 
                                      ,q62_vlrbasecalc 
                                      ,q62_vlrissqn 
                                      ,q62_obs 
                       )
                values (
                                $this->q62_sequencial 
                               ,$this->q62_issnotaavulsa 
                               ,$this->q62_qtd 
                               ,'$this->q62_discriminacao' 
                               ,$this->q62_vlruni 
                               ,$this->q62_aliquota 
                               ,$this->q62_vlrdeducao 
                               ,$this->q62_vlrtotal 
                               ,$this->q62_vlrbasecalc 
                               ,$this->q62_vlrissqn 
                               ,'$this->q62_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Servicos Prestados ($this->q62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Servicos Prestados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Servicos Prestados ($this->q62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q62_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q62_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10613,'$this->q62_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1831,10613,'','".AddSlashes(pg_result($resaco,0,'q62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10603,'','".AddSlashes(pg_result($resaco,0,'q62_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10604,'','".AddSlashes(pg_result($resaco,0,'q62_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10605,'','".AddSlashes(pg_result($resaco,0,'q62_discriminacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10606,'','".AddSlashes(pg_result($resaco,0,'q62_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10607,'','".AddSlashes(pg_result($resaco,0,'q62_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10608,'','".AddSlashes(pg_result($resaco,0,'q62_vlrdeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10609,'','".AddSlashes(pg_result($resaco,0,'q62_vlrtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10610,'','".AddSlashes(pg_result($resaco,0,'q62_vlrbasecalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10611,'','".AddSlashes(pg_result($resaco,0,'q62_vlrissqn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1831,10612,'','".AddSlashes(pg_result($resaco,0,'q62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q62_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issnotaavulsaservico set ";
     $virgula = "";
     if(trim($this->q62_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_sequencial"])){ 
       $sql  .= $virgula." q62_sequencial = $this->q62_sequencial ";
       $virgula = ",";
       if(trim($this->q62_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q62_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_issnotaavulsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_issnotaavulsa"])){ 
       $sql  .= $virgula." q62_issnotaavulsa = $this->q62_issnotaavulsa ";
       $virgula = ",";
       if(trim($this->q62_issnotaavulsa) == null ){ 
         $this->erro_sql = " Campo Número da Nota nao Informado.";
         $this->erro_campo = "q62_issnotaavulsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_qtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_qtd"])){ 
       $sql  .= $virgula." q62_qtd = $this->q62_qtd ";
       $virgula = ",";
       if(trim($this->q62_qtd) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "q62_qtd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_discriminacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_discriminacao"])){ 
       $sql  .= $virgula." q62_discriminacao = '$this->q62_discriminacao' ";
       $virgula = ",";
       if(trim($this->q62_discriminacao) == null ){ 
         $this->erro_sql = " Campo Discriminação do Serviço nao Informado.";
         $this->erro_campo = "q62_discriminacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_vlruni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_vlruni"])){ 
       $sql  .= $virgula." q62_vlruni = $this->q62_vlruni ";
       $virgula = ",";
       if(trim($this->q62_vlruni) == null ){ 
         $this->erro_sql = " Campo Valor Unitário nao Informado.";
         $this->erro_campo = "q62_vlruni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_aliquota"])){ 
       $sql  .= $virgula." q62_aliquota = $this->q62_aliquota ";
       $virgula = ",";
       if(trim($this->q62_aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "q62_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_vlrdeducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrdeducao"])){ 
        if(trim($this->q62_vlrdeducao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrdeducao"])){ 
           $this->q62_vlrdeducao = "0" ; 
        } 
       $sql  .= $virgula." q62_vlrdeducao = $this->q62_vlrdeducao ";
       $virgula = ",";
     }
     if(trim($this->q62_vlrtotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrtotal"])){ 
       $sql  .= $virgula." q62_vlrtotal = $this->q62_vlrtotal ";
       $virgula = ",";
       if(trim($this->q62_vlrtotal) == null ){ 
         $this->erro_sql = " Campo Valor Total nao Informado.";
         $this->erro_campo = "q62_vlrtotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q62_vlrbasecalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrbasecalc"])){ 
        if(trim($this->q62_vlrbasecalc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrbasecalc"])){ 
           $this->q62_vlrbasecalc = "0" ; 
        } 
       $sql  .= $virgula." q62_vlrbasecalc = $this->q62_vlrbasecalc ";
       $virgula = ",";
     }
     if(trim($this->q62_vlrissqn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrissqn"])){ 
        if(trim($this->q62_vlrissqn)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrissqn"])){ 
           $this->q62_vlrissqn = "0" ; 
        } 
       $sql  .= $virgula." q62_vlrissqn = $this->q62_vlrissqn ";
       $virgula = ",";
     }
     if(trim($this->q62_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q62_obs"])){ 
       $sql  .= $virgula." q62_obs = '$this->q62_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q62_sequencial!=null){
       $sql .= " q62_sequencial = $this->q62_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q62_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10613,'$this->q62_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1831,10613,'".AddSlashes(pg_result($resaco,$conresaco,'q62_sequencial'))."','$this->q62_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_issnotaavulsa"]))
           $resac = db_query("insert into db_acount values($acount,1831,10603,'".AddSlashes(pg_result($resaco,$conresaco,'q62_issnotaavulsa'))."','$this->q62_issnotaavulsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_qtd"]))
           $resac = db_query("insert into db_acount values($acount,1831,10604,'".AddSlashes(pg_result($resaco,$conresaco,'q62_qtd'))."','$this->q62_qtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_discriminacao"]))
           $resac = db_query("insert into db_acount values($acount,1831,10605,'".AddSlashes(pg_result($resaco,$conresaco,'q62_discriminacao'))."','$this->q62_discriminacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_vlruni"]))
           $resac = db_query("insert into db_acount values($acount,1831,10606,'".AddSlashes(pg_result($resaco,$conresaco,'q62_vlruni'))."','$this->q62_vlruni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_aliquota"]))
           $resac = db_query("insert into db_acount values($acount,1831,10607,'".AddSlashes(pg_result($resaco,$conresaco,'q62_aliquota'))."','$this->q62_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrdeducao"]))
           $resac = db_query("insert into db_acount values($acount,1831,10608,'".AddSlashes(pg_result($resaco,$conresaco,'q62_vlrdeducao'))."','$this->q62_vlrdeducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrtotal"]))
           $resac = db_query("insert into db_acount values($acount,1831,10609,'".AddSlashes(pg_result($resaco,$conresaco,'q62_vlrtotal'))."','$this->q62_vlrtotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrbasecalc"]))
           $resac = db_query("insert into db_acount values($acount,1831,10610,'".AddSlashes(pg_result($resaco,$conresaco,'q62_vlrbasecalc'))."','$this->q62_vlrbasecalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_vlrissqn"]))
           $resac = db_query("insert into db_acount values($acount,1831,10611,'".AddSlashes(pg_result($resaco,$conresaco,'q62_vlrissqn'))."','$this->q62_vlrissqn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q62_obs"]))
           $resac = db_query("insert into db_acount values($acount,1831,10612,'".AddSlashes(pg_result($resaco,$conresaco,'q62_obs'))."','$this->q62_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Servicos Prestados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Servicos Prestados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q62_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q62_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10613,'$q62_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1831,10613,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10603,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10604,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10605,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_discriminacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10606,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10607,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10608,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_vlrdeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10609,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_vlrtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10610,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_vlrbasecalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10611,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_vlrissqn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1831,10612,'','".AddSlashes(pg_result($resaco,$iresaco,'q62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issnotaavulsaservico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q62_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q62_sequencial = $q62_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Servicos Prestados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Servicos Prestados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q62_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issnotaavulsaservico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q62_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsaservico ";
     $sql .= "      inner join issnotaavulsa  on  issnotaavulsa.q51_sequencial = issnotaavulsaservico.q62_issnotaavulsa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q62_sequencial!=null ){
         $sql2 .= " where issnotaavulsaservico.q62_sequencial = $q62_sequencial "; 
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
   function sql_query_file ( $q62_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsaservico ";
     $sql2 = "";
     if($dbwhere==""){
       if($q62_sequencial!=null ){
         $sql2 .= " where issnotaavulsaservico.q62_sequencial = $q62_sequencial "; 
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
   function sql_query_total ( $q62_sequencial=null,$ordem=null,$dbwhere=""){ 
     
     $sql  = "select ";
	   $sql .= "q62_sequencial,";
     $sql .= "q62_issnotaavulsa,q62_qtd,";
     $sql .= "q62_discriminacao,";
     $sql .= "q62_vlruni,";
     $sql .= "q62_aliquota,";
     $sql .= "q62_vlrdeducao,";
     $sql .= "q62_vlrtotal,q62_vlrbasecalc,q62_vlrissqn";
     $sql .= " from issnotaavulsaservico ";
     $sql2 = "";
     if($dbwhere==""){
       if($q62_sequencial!=null ){
         $sql2 .= " where issnotaavulsaservico.q62_issnotaavulsa = $q62_issnotaavulsa "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql .= " union ";
     $sql .= "select ";
	   $sql .= "9999999 as q62_sequencial,";
     $sql .= "9999999 as q62_issnotaavulsa,0 as q62_qtd,";
     $sql .= "'TOTAL:' AS q62_discriminacao,";
     $sql .= "sum(q62_vlruni) as q62_vrluni,";
     $sql .= "null as q62_aliquota,";
     $sql .= "sum(q62_vlrdeducao) as q62_vrldeducao,";
     $sql .= "sum(q62_vlrtotal) as q62_clrtotal,";
     $sql .= "sum(q62_vlrbasecalc) as q62_vlrbasecalc,";
     $sql .= "sum(q62_vlrissqn)  as q62_vlrissqn";
     $sql .= " from issnotaavulsaservico ";
     $sql2 = "";
     if($dbwhere==""){
       if($q62_sequencial!=null ){
         $sql2 .= " where issnotaavulsaservico.q62_issnotaavulsa = $q62_issnotaavulsa "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
    // $sql .= "group by q62_aliquota,q62_sequencial,q62_issnotaavulsa,q62_qtd,q62_discriminacao "; 
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