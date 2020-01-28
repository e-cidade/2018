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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensdepreciacao
class cl_bensdepreciacao { 
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
   var $t44_sequencial = 0; 
   var $t44_bens = 0; 
   var $t44_benstipoaquisicao = 0; 
   var $t44_benstipodepreciacao = 0; 
   var $t44_vidautil = 0; 
   var $t44_valoratual = 0; 
   var $t44_valorresidual = 0; 
   var $t44_ultimaavaliacao_dia = null; 
   var $t44_ultimaavaliacao_mes = null; 
   var $t44_ultimaavaliacao_ano = null; 
   var $t44_ultimaavaliacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t44_sequencial = int4 = Código 
                 t44_bens = int4 = Código so Bem 
                 t44_benstipoaquisicao = int4 = Tipo Aquisição 
                 t44_benstipodepreciacao = int4 = Tipo depreciação 
                 t44_vidautil = int4 = Vida Útil 
                 t44_valoratual = numeric(10) = Valor Atual 
                 t44_valorresidual = numeric(10) = Valor Residual 
                 t44_ultimaavaliacao = date =  
                 ";
   //funcao construtor da classe 
   function cl_bensdepreciacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensdepreciacao"); 
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
       $this->t44_sequencial = ($this->t44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_sequencial"]:$this->t44_sequencial);
       $this->t44_bens = ($this->t44_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_bens"]:$this->t44_bens);
       $this->t44_benstipoaquisicao = ($this->t44_benstipoaquisicao == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_benstipoaquisicao"]:$this->t44_benstipoaquisicao);
       $this->t44_benstipodepreciacao = ($this->t44_benstipodepreciacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_benstipodepreciacao"]:$this->t44_benstipodepreciacao);
       $this->t44_vidautil = ($this->t44_vidautil == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_vidautil"]:$this->t44_vidautil);
       $this->t44_valoratual = ($this->t44_valoratual == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_valoratual"]:$this->t44_valoratual);
       $this->t44_valorresidual = ($this->t44_valorresidual == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_valorresidual"]:$this->t44_valorresidual);
       if($this->t44_ultimaavaliacao == ""){
         $this->t44_ultimaavaliacao_dia = ($this->t44_ultimaavaliacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_dia"]:$this->t44_ultimaavaliacao_dia);
         $this->t44_ultimaavaliacao_mes = ($this->t44_ultimaavaliacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_mes"]:$this->t44_ultimaavaliacao_mes);
         $this->t44_ultimaavaliacao_ano = ($this->t44_ultimaavaliacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_ano"]:$this->t44_ultimaavaliacao_ano);
         if($this->t44_ultimaavaliacao_dia != ""){
            $this->t44_ultimaavaliacao = $this->t44_ultimaavaliacao_ano."-".$this->t44_ultimaavaliacao_mes."-".$this->t44_ultimaavaliacao_dia;
         }
       }
     }else{
       $this->t44_sequencial = ($this->t44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t44_sequencial"]:$this->t44_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t44_sequencial){ 
      $this->atualizacampos();
     if($this->t44_bens == null ){ 
       $this->erro_sql = " Campo Código so Bem nao Informado.";
       $this->erro_campo = "t44_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_benstipoaquisicao == null ){ 
       $this->erro_sql = " Campo Tipo Aquisição nao Informado.";
       $this->erro_campo = "t44_benstipoaquisicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_benstipodepreciacao == null ){ 
       $this->erro_sql = " Campo Tipo depreciação nao Informado.";
       $this->erro_campo = "t44_benstipodepreciacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_vidautil == null ){ 
       $this->erro_sql = " Campo Vida Útil nao Informado.";
       $this->erro_campo = "t44_vidautil";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_valoratual == null ){ 
       $this->erro_sql = " Campo Valor Atual nao Informado.";
       $this->erro_campo = "t44_valoratual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_valorresidual == null ){ 
       $this->erro_sql = " Campo Valor Residual nao Informado.";
       $this->erro_campo = "t44_valorresidual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t44_ultimaavaliacao == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "t44_ultimaavaliacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t44_sequencial == "" || $t44_sequencial == null ){
       $result = db_query("select nextval('bensdepreciacao_t44_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensdepreciacao_t44_sequencial_seq do campo: t44_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t44_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensdepreciacao_t44_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t44_sequencial)){
         $this->erro_sql = " Campo t44_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t44_sequencial = $t44_sequencial; 
       }
     }
     if(($this->t44_sequencial == null) || ($this->t44_sequencial == "") ){ 
       $this->erro_sql = " Campo t44_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensdepreciacao(
                                       t44_sequencial 
                                      ,t44_bens 
                                      ,t44_benstipoaquisicao 
                                      ,t44_benstipodepreciacao 
                                      ,t44_vidautil 
                                      ,t44_valoratual 
                                      ,t44_valorresidual 
                                      ,t44_ultimaavaliacao 
                       )
                values (
                                $this->t44_sequencial 
                               ,$this->t44_bens 
                               ,$this->t44_benstipoaquisicao 
                               ,$this->t44_benstipodepreciacao 
                               ,$this->t44_vidautil 
                               ,$this->t44_valoratual 
                               ,$this->t44_valorresidual 
                               ,".($this->t44_ultimaavaliacao == "null" || $this->t44_ultimaavaliacao == ""?"null":"'".$this->t44_ultimaavaliacao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Depreciação de Bens ($this->t44_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Depreciação de Bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Depreciação de Bens ($this->t44_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t44_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t44_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18456,'$this->t44_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3263,18456,'','".AddSlashes(pg_result($resaco,0,'t44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18457,'','".AddSlashes(pg_result($resaco,0,'t44_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18455,'','".AddSlashes(pg_result($resaco,0,'t44_benstipoaquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18452,'','".AddSlashes(pg_result($resaco,0,'t44_benstipodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18453,'','".AddSlashes(pg_result($resaco,0,'t44_vidautil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18451,'','".AddSlashes(pg_result($resaco,0,'t44_valoratual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18450,'','".AddSlashes(pg_result($resaco,0,'t44_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3263,18454,'','".AddSlashes(pg_result($resaco,0,'t44_ultimaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t44_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bensdepreciacao set ";
     $virgula = "";
     if(trim($this->t44_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_sequencial"])){ 
       $sql  .= $virgula." t44_sequencial = $this->t44_sequencial ";
       $virgula = ",";
       if(trim($this->t44_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t44_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_bens"])){ 
       $sql  .= $virgula." t44_bens = $this->t44_bens ";
       $virgula = ",";
       if(trim($this->t44_bens) == null ){ 
         $this->erro_sql = " Campo Código so Bem nao Informado.";
         $this->erro_campo = "t44_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_benstipoaquisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_benstipoaquisicao"])){ 
       $sql  .= $virgula." t44_benstipoaquisicao = $this->t44_benstipoaquisicao ";
       $virgula = ",";
       if(trim($this->t44_benstipoaquisicao) == null ){ 
         $this->erro_sql = " Campo Tipo Aquisição nao Informado.";
         $this->erro_campo = "t44_benstipoaquisicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_benstipodepreciacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_benstipodepreciacao"])){ 
       $sql  .= $virgula." t44_benstipodepreciacao = $this->t44_benstipodepreciacao ";
       $virgula = ",";
       if(trim($this->t44_benstipodepreciacao) == null ){ 
         $this->erro_sql = " Campo Tipo depreciação nao Informado.";
         $this->erro_campo = "t44_benstipodepreciacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_vidautil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_vidautil"])){ 
       $sql  .= $virgula." t44_vidautil = $this->t44_vidautil ";
       $virgula = ",";
       if(trim($this->t44_vidautil) == null ){ 
         $this->erro_sql = " Campo Vida Útil nao Informado.";
         $this->erro_campo = "t44_vidautil";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_valoratual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_valoratual"])){ 
       $sql  .= $virgula." t44_valoratual = $this->t44_valoratual ";
       $virgula = ",";
       if(trim($this->t44_valoratual) == null ){ 
         $this->erro_sql = " Campo Valor Atual nao Informado.";
         $this->erro_campo = "t44_valoratual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_valorresidual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_valorresidual"])){ 
       $sql  .= $virgula." t44_valorresidual = $this->t44_valorresidual ";
       $virgula = ",";
       if(trim($this->t44_valorresidual) == null ){ 
         $this->erro_sql = " Campo Valor Residual nao Informado.";
         $this->erro_campo = "t44_valorresidual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t44_ultimaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_dia"] !="") ){ 
       $sql  .= $virgula." t44_ultimaavaliacao = '$this->t44_ultimaavaliacao' ";
       $virgula = ",";
       if(trim($this->t44_ultimaavaliacao) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "t44_ultimaavaliacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao_dia"])){ 
         $sql  .= $virgula." t44_ultimaavaliacao = null ";
         $virgula = ",";
         if(trim($this->t44_ultimaavaliacao) == null ){ 
           $this->erro_sql = " Campo  nao Informado.";
           $this->erro_campo = "t44_ultimaavaliacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($t44_sequencial!=null){
       $sql .= " t44_sequencial = $this->t44_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t44_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18456,'$this->t44_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_sequencial"]) || $this->t44_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3263,18456,'".AddSlashes(pg_result($resaco,$conresaco,'t44_sequencial'))."','$this->t44_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_bens"]) || $this->t44_bens != "")
           $resac = db_query("insert into db_acount values($acount,3263,18457,'".AddSlashes(pg_result($resaco,$conresaco,'t44_bens'))."','$this->t44_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_benstipoaquisicao"]) || $this->t44_benstipoaquisicao != "")
           $resac = db_query("insert into db_acount values($acount,3263,18455,'".AddSlashes(pg_result($resaco,$conresaco,'t44_benstipoaquisicao'))."','$this->t44_benstipoaquisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_benstipodepreciacao"]) || $this->t44_benstipodepreciacao != "")
           $resac = db_query("insert into db_acount values($acount,3263,18452,'".AddSlashes(pg_result($resaco,$conresaco,'t44_benstipodepreciacao'))."','$this->t44_benstipodepreciacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_vidautil"]) || $this->t44_vidautil != "")
           $resac = db_query("insert into db_acount values($acount,3263,18453,'".AddSlashes(pg_result($resaco,$conresaco,'t44_vidautil'))."','$this->t44_vidautil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_valoratual"]) || $this->t44_valoratual != "")
           $resac = db_query("insert into db_acount values($acount,3263,18451,'".AddSlashes(pg_result($resaco,$conresaco,'t44_valoratual'))."','$this->t44_valoratual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_valorresidual"]) || $this->t44_valorresidual != "")
           $resac = db_query("insert into db_acount values($acount,3263,18450,'".AddSlashes(pg_result($resaco,$conresaco,'t44_valorresidual'))."','$this->t44_valorresidual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t44_ultimaavaliacao"]) || $this->t44_ultimaavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3263,18454,'".AddSlashes(pg_result($resaco,$conresaco,'t44_ultimaavaliacao'))."','$this->t44_ultimaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Depreciação de Bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Depreciação de Bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t44_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t44_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18456,'$t44_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3263,18456,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18457,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18455,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_benstipoaquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18452,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_benstipodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18453,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_vidautil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18451,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_valoratual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18450,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3263,18454,'','".AddSlashes(pg_result($resaco,$iresaco,'t44_ultimaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensdepreciacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t44_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t44_sequencial = $t44_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Depreciação de Bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Depreciação de Bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t44_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensdepreciacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t44_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdepreciacao ";
     $sql .= "      inner join bens  on  bens.t52_bem = bensdepreciacao.t44_bens";
     $sql .= "      inner join benstipoaquisicao  on  benstipoaquisicao.t45_sequencial = bensdepreciacao.t44_benstipoaquisicao";
     $sql .= "      inner join benstipodepreciacao  on  benstipodepreciacao.t46_sequencial = bensdepreciacao.t44_benstipodepreciacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql2 = "";
     if($dbwhere==""){
       if($t44_sequencial!=null ){
         $sql2 .= " where bensdepreciacao.t44_sequencial = $t44_sequencial "; 
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
   function sql_query_file ( $t44_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdepreciacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($t44_sequencial!=null ){
         $sql2 .= " where bensdepreciacao.t44_sequencial = $t44_sequencial "; 
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
  
  function sql_query_bem ( $t44_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

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
     $sql .= " from bensdepreciacao ";
     $sql .= "      inner join  bens on t44_bens = t52_bem ";
     $sql .= "      left  join  bensbaix on t55_codbem = t52_bem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t44_sequencial!=null ){
         $sql2 .= " where bensdepreciacao.t44_sequencial = $t44_sequencial "; 
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