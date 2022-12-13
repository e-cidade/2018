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

//MODULO: atendimento
//CLASSE DA ENTIDADE atenditem
class cl_atenditem { 
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
   var $at05_seq = 0; 
   var $at05_codatend = 0; 
   var $at05_solicitado = null; 
   var $at05_feito = null; 
   var $at05_tipo = 0; 
   var $at05_data_dia = null; 
   var $at05_data_mes = null; 
   var $at05_data_ano = null; 
   var $at05_data = null; 
   var $at05_perc = 0; 
   var $at05_horafim = null; 
   var $at05_horaini = null; 
   var $at05_prioridade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at05_seq = int4 = Sequência 
                 at05_codatend = int4 = Código de atendimento 
                 at05_solicitado = text = Solicitação 
                 at05_feito = text = Executado 
                 at05_tipo = int4 = tipo do atendimento 
                 at05_data = date = Prazo Cliente 
                 at05_perc = float8 = Percentual 
                 at05_horafim = char(5) = Hora final 
                 at05_horaini = char(5) = Hora inicial 
                 at05_prioridade = int4 = Prioridade 
                 ";
   //funcao construtor da classe 
   function cl_atenditem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atenditem"); 
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
       $this->at05_seq = ($this->at05_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_seq"]:$this->at05_seq);
       $this->at05_codatend = ($this->at05_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_codatend"]:$this->at05_codatend);
       $this->at05_solicitado = ($this->at05_solicitado == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_solicitado"]:$this->at05_solicitado);
       $this->at05_feito = ($this->at05_feito == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_feito"]:$this->at05_feito);
       $this->at05_tipo = ($this->at05_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_tipo"]:$this->at05_tipo);
       if($this->at05_data == ""){
         $this->at05_data_dia = ($this->at05_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_data_dia"]:$this->at05_data_dia);
         $this->at05_data_mes = ($this->at05_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_data_mes"]:$this->at05_data_mes);
         $this->at05_data_ano = ($this->at05_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_data_ano"]:$this->at05_data_ano);
         if($this->at05_data_dia != ""){
            $this->at05_data = $this->at05_data_ano."-".$this->at05_data_mes."-".$this->at05_data_dia;
         }
       }
       $this->at05_perc = ($this->at05_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_perc"]:$this->at05_perc);
       $this->at05_horafim = ($this->at05_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_horafim"]:$this->at05_horafim);
       $this->at05_horaini = ($this->at05_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_horaini"]:$this->at05_horaini);
       $this->at05_prioridade = ($this->at05_prioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_prioridade"]:$this->at05_prioridade);
     }else{
       $this->at05_seq = ($this->at05_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_seq"]:$this->at05_seq);
       $this->at05_codatend = ($this->at05_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at05_codatend"]:$this->at05_codatend);
     }
   }
   // funcao para inclusao
   function incluir ($at05_seq,$at05_codatend){ 
      $this->atualizacampos();
     if($this->at05_solicitado == null ){ 
       $this->erro_sql = " Campo Solicitação nao Informado.";
       $this->erro_campo = "at05_solicitado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at05_tipo == null ){ 
       $this->erro_sql = " Campo tipo do atendimento nao Informado.";
       $this->erro_campo = "at05_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at05_data == null ){ 
       $this->at05_data = "null";
     }
     if($this->at05_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "at05_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at05_horafim == null ){ 
       $this->erro_sql = " Campo Hora final nao Informado.";
       $this->erro_campo = "at05_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at05_horaini == null ){ 
       $this->erro_sql = " Campo Hora inicial nao Informado.";
       $this->erro_campo = "at05_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at05_prioridade == null ){ 
       $this->erro_sql = " Campo Prioridade nao Informado.";
       $this->erro_campo = "at05_prioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at05_seq == "" || $at05_seq == null ){
       $result = db_query("select nextval('atenditem_at05_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atenditem_at05_seq_seq do campo: at05_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at05_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atenditem_at05_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $at05_seq)){
         $this->erro_sql = " Campo at05_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at05_seq = $at05_seq; 
       }
     }
     if(($this->at05_seq == null) || ($this->at05_seq == "") ){ 
       $this->erro_sql = " Campo at05_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->at05_codatend == null) || ($this->at05_codatend == "") ){ 
       $this->erro_sql = " Campo at05_codatend nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atenditem(
                                       at05_seq 
                                      ,at05_codatend 
                                      ,at05_solicitado 
                                      ,at05_feito 
                                      ,at05_tipo 
                                      ,at05_data 
                                      ,at05_perc 
                                      ,at05_horafim 
                                      ,at05_horaini 
                                      ,at05_prioridade 
                       )
                values (
                                $this->at05_seq 
                               ,$this->at05_codatend 
                               ,'$this->at05_solicitado' 
                               ,'$this->at05_feito' 
                               ,$this->at05_tipo 
                               ,".($this->at05_data == "null" || $this->at05_data == ""?"null":"'".$this->at05_data."'")." 
                               ,$this->at05_perc 
                               ,'$this->at05_horafim' 
                               ,'$this->at05_horaini' 
                               ,$this->at05_prioridade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela com os itens de cada atendimento ($this->at05_seq."-".$this->at05_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela com os itens de cada atendimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela com os itens de cada atendimento ($this->at05_seq."-".$this->at05_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at05_seq."-".$this->at05_codatend;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at05_seq,$this->at05_codatend));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5095,'$this->at05_seq','I')");
       $resac = db_query("insert into db_acountkey values($acount,5092,'$this->at05_codatend','I')");
       $resac = db_query("insert into db_acount values($acount,724,5095,'','".AddSlashes(pg_result($resaco,0,'at05_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,5092,'','".AddSlashes(pg_result($resaco,0,'at05_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,5093,'','".AddSlashes(pg_result($resaco,0,'at05_solicitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,5094,'','".AddSlashes(pg_result($resaco,0,'at05_feito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,5133,'','".AddSlashes(pg_result($resaco,0,'at05_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,5136,'','".AddSlashes(pg_result($resaco,0,'at05_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,8364,'','".AddSlashes(pg_result($resaco,0,'at05_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,9881,'','".AddSlashes(pg_result($resaco,0,'at05_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,9880,'','".AddSlashes(pg_result($resaco,0,'at05_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,724,9964,'','".AddSlashes(pg_result($resaco,0,'at05_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at05_seq=null,$at05_codatend=null) { 
      $this->atualizacampos();
     $sql = " update atenditem set ";
     $virgula = "";
     if(trim($this->at05_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_seq"])){ 
       $sql  .= $virgula." at05_seq = $this->at05_seq ";
       $virgula = ",";
       if(trim($this->at05_seq) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "at05_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_codatend"])){ 
       $sql  .= $virgula." at05_codatend = $this->at05_codatend ";
       $virgula = ",";
       if(trim($this->at05_codatend) == null ){ 
         $this->erro_sql = " Campo Código de atendimento nao Informado.";
         $this->erro_campo = "at05_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_solicitado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_solicitado"])){ 
       $sql  .= $virgula." at05_solicitado = '$this->at05_solicitado' ";
       $virgula = ",";
       if(trim($this->at05_solicitado) == null ){ 
         $this->erro_sql = " Campo Solicitação nao Informado.";
         $this->erro_campo = "at05_solicitado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_feito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_feito"])){ 
       $sql  .= $virgula." at05_feito = '$this->at05_feito' ";
       $virgula = ",";
     }
     if(trim($this->at05_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_tipo"])){ 
       $sql  .= $virgula." at05_tipo = $this->at05_tipo ";
       $virgula = ",";
       if(trim($this->at05_tipo) == null ){ 
         $this->erro_sql = " Campo tipo do atendimento nao Informado.";
         $this->erro_campo = "at05_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at05_data_dia"] !="") ){ 
       $sql  .= $virgula." at05_data = '$this->at05_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at05_data_dia"])){ 
         $sql  .= $virgula." at05_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at05_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_perc"])){ 
       $sql  .= $virgula." at05_perc = $this->at05_perc ";
       $virgula = ",";
       if(trim($this->at05_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "at05_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_horafim"])){ 
       $sql  .= $virgula." at05_horafim = '$this->at05_horafim' ";
       $virgula = ",";
       if(trim($this->at05_horafim) == null ){ 
         $this->erro_sql = " Campo Hora final nao Informado.";
         $this->erro_campo = "at05_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_horaini"])){ 
       $sql  .= $virgula." at05_horaini = '$this->at05_horaini' ";
       $virgula = ",";
       if(trim($this->at05_horaini) == null ){ 
         $this->erro_sql = " Campo Hora inicial nao Informado.";
         $this->erro_campo = "at05_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at05_prioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at05_prioridade"])){ 
       $sql  .= $virgula." at05_prioridade = $this->at05_prioridade ";
       $virgula = ",";
       if(trim($this->at05_prioridade) == null ){ 
         $this->erro_sql = " Campo Prioridade nao Informado.";
         $this->erro_campo = "at05_prioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at05_seq!=null){
       $sql .= " at05_seq = $this->at05_seq";
     }
     if($at05_codatend!=null){
       $sql .= " and  at05_codatend = $this->at05_codatend";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at05_seq,$this->at05_codatend));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5095,'$this->at05_seq','A')");
         $resac = db_query("insert into db_acountkey values($acount,5092,'$this->at05_codatend','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_seq"]))
           $resac = db_query("insert into db_acount values($acount,724,5095,'".AddSlashes(pg_result($resaco,$conresaco,'at05_seq'))."','$this->at05_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_codatend"]))
           $resac = db_query("insert into db_acount values($acount,724,5092,'".AddSlashes(pg_result($resaco,$conresaco,'at05_codatend'))."','$this->at05_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_solicitado"]))
           $resac = db_query("insert into db_acount values($acount,724,5093,'".AddSlashes(pg_result($resaco,$conresaco,'at05_solicitado'))."','$this->at05_solicitado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_feito"]))
           $resac = db_query("insert into db_acount values($acount,724,5094,'".AddSlashes(pg_result($resaco,$conresaco,'at05_feito'))."','$this->at05_feito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_tipo"]))
           $resac = db_query("insert into db_acount values($acount,724,5133,'".AddSlashes(pg_result($resaco,$conresaco,'at05_tipo'))."','$this->at05_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_data"]))
           $resac = db_query("insert into db_acount values($acount,724,5136,'".AddSlashes(pg_result($resaco,$conresaco,'at05_data'))."','$this->at05_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_perc"]))
           $resac = db_query("insert into db_acount values($acount,724,8364,'".AddSlashes(pg_result($resaco,$conresaco,'at05_perc'))."','$this->at05_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_horafim"]))
           $resac = db_query("insert into db_acount values($acount,724,9881,'".AddSlashes(pg_result($resaco,$conresaco,'at05_horafim'))."','$this->at05_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_horaini"]))
           $resac = db_query("insert into db_acount values($acount,724,9880,'".AddSlashes(pg_result($resaco,$conresaco,'at05_horaini'))."','$this->at05_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at05_prioridade"]))
           $resac = db_query("insert into db_acount values($acount,724,9964,'".AddSlashes(pg_result($resaco,$conresaco,'at05_prioridade'))."','$this->at05_prioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com os itens de cada atendimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at05_seq."-".$this->at05_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com os itens de cada atendimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at05_seq."-".$this->at05_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at05_seq."-".$this->at05_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at05_seq=null,$at05_codatend=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at05_seq,$at05_codatend));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5095,'$at05_seq','E')");
         $resac = db_query("insert into db_acountkey values($acount,5092,'$at05_codatend','E')");
         $resac = db_query("insert into db_acount values($acount,724,5095,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,5092,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,5093,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_solicitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,5094,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_feito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,5133,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,5136,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,8364,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,9881,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,9880,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,724,9964,'','".AddSlashes(pg_result($resaco,$iresaco,'at05_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atenditem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at05_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at05_seq = $at05_seq ";
        }
        if($at05_codatend != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at05_codatend = $at05_codatend ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com os itens de cada atendimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at05_seq."-".$at05_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com os itens de cada atendimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at05_seq."-".$at05_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at05_seq."-".$at05_codatend;
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
        $this->erro_sql   = "Record Vazio na Tabela:atenditem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at05_seq=null,$at05_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atenditem ";
     $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atenditem.at05_codatend";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = atendimento.at02_codcli";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendimento.at02_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($at05_seq!=null ){
         $sql2 .= " where atenditem.at05_seq = $at05_seq "; 
       } 
       if($at05_codatend!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atenditem.at05_codatend = $at05_codatend "; 
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
   function sql_query_file ( $at05_seq=null,$at05_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atenditem ";
     $sql2 = "";
     if($dbwhere==""){
       if($at05_seq!=null ){
         $sql2 .= " where atenditem.at05_seq = $at05_seq "; 
       } 
       if($at05_codatend!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atenditem.at05_codatend = $at05_codatend "; 
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
   function sql_query_mod ( $at05_seq=null,$at05_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
    $sql .= " from atenditem ";
    $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atenditem.at05_codatend";
    $sql .= "      inner join clientes     on  clientes.at01_codcli = atendimento.at02_codcli";
    $sql .= "      left  join atenditemmod on  atenditemmod.at22_atenditem = atenditem.at05_seq";
    $sql .= "      left  join db_modulos   on  db_modulos.id_item = atenditemmod.at22_modulo";
    $sql2 = "";
    if($dbwhere==""){
      if($at05_seq!=null ){
        $sql2 .= " where atenditem.at05_seq = $at05_seq "; 
      } 
      if($at05_codatend!=null ){
        if($sql2!=""){
           $sql2 .= " and ";
        }else{
           $sql2 .= " where ";
        } 
        $sql2 .= " atenditem.at05_codatend = $at05_codatend "; 
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
   function sql_query_tarefa ( $at05_seq=null,$at05_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
    $sql .= " from atenditem ";
    $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atenditem.at05_codatend";
    $sql .= "      inner join clientes     on  clientes.at01_codcli = atendimento.at02_codcli";
    $sql .= "      left  join tarefaitem   on  tarefaitem.at44_atenditem = atenditem.at05_seq";
    $sql .= "      left  join atenditemlanc on  atenditemlanc.at07_atenditem = atenditem.at05_codatend";
	 $sql .= "      left  join db_usuarios		on  atenditemlanc.at07_usuariolanc = db_usuarios.id_usuario";
    $sql2 = "";
    if($dbwhere==""){
      if($at05_seq!=null ){
        $sql2 .= " where atenditem.at05_seq = $at05_seq "; 
      } 
      if($at05_codatend!=null ){
        if($sql2!=""){
           $sql2 .= " and ";
        }else{
           $sql2 .= " where ";
        } 
        $sql2 .= " atenditem.at05_codatend = $at05_codatend "; 
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