<?php

/**
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

class cl_confvencissqnvariavel { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $q144_sequencial = 0; 
   var $q144_ano = 0; 
   var $q144_codvenc = 0; 
   var $q144_receita = 0; 
   var $q144_tipo = 0; 
   var $q144_hist = 0; 
   var $q144_diavenc = 1;
   var $q144_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q144_sequencial = int4 = Código Sequencial 
                 q144_ano = int4 = Ano Competência 
                 q144_codvenc = int4 = Vencimento 
                 q144_receita = int4 = Receita 
                 q144_tipo = int4 = Tipo de Débito 
                 q144_hist = int4 = Histórico 
                 q144_diavenc = int4 = Dia do Vencimento 
                 q144_valor = float4 = Valor Mínimo 
                 ";
   //funcao construtor da classe 
   function cl_confvencissqnvariavel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("confvencissqnvariavel"); 
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
       $this->q144_sequencial = ($this->q144_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_sequencial"]:$this->q144_sequencial);
       $this->q144_ano = ($this->q144_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_ano"]:$this->q144_ano);
       $this->q144_codvenc = ($this->q144_codvenc == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_codvenc"]:$this->q144_codvenc);
       $this->q144_receita = ($this->q144_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_receita"]:$this->q144_receita);
       $this->q144_tipo = ($this->q144_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_tipo"]:$this->q144_tipo);
       $this->q144_hist = ($this->q144_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_hist"]:$this->q144_hist);
       $this->q144_diavenc = ($this->q144_diavenc == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_diavenc"]:$this->q144_diavenc);
       $this->q144_valor = ($this->q144_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_valor"]:$this->q144_valor);
     }else{
       $this->q144_sequencial = ($this->q144_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q144_sequencial"]:$this->q144_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q144_sequencial){ 
      $this->atualizacampos();
     if($this->q144_ano == null ){ 
       $this->erro_sql = " Campo Ano Competência nao Informado.";
       $this->erro_campo = "q144_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_codvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "q144_codvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "q144_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito nao Informado.";
       $this->erro_campo = "q144_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_hist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "q144_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_diavenc == null ){ 
       $this->erro_sql = " Campo Dia do Vencimento nao Informado.";
       $this->erro_campo = "q144_diavenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->q144_diavenc < 1 || $this->q144_diavenc > 31) {
       $this->erro_sql    = " Campo Dia Vencimento não pode ser maior ou menor que o período do mês.";
       $this->erro_campo  = "q144_diavenc";
       $this->erro_banco  = "";
       $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q144_valor == null ){ 
       $this->q144_valor = "0";
     }
     if($q144_sequencial == "" || $q144_sequencial == null ){
       $result = @db_query("select nextval('confvencissqnvariavel_q144_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: confvencissqnvariavel_q144_sequencial_seq do campo: q144_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q144_sequencial = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from confvencissqnvariavel_q144_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q144_sequencial)){
         $this->erro_sql = " Campo q144_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q144_sequencial = $q144_sequencial; 
       }
     }
     if(($this->q144_sequencial == null) || ($this->q144_sequencial == "") ){ 
       $this->erro_sql = " Campo q144_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @db_query("insert into confvencissqnvariavel(
                                       q144_sequencial 
                                      ,q144_ano 
                                      ,q144_codvenc 
                                      ,q144_receita 
                                      ,q144_tipo 
                                      ,q144_hist 
                                      ,q144_diavenc 
                                      ,q144_valor 
                       )
                values (
                                $this->q144_sequencial 
                               ,$this->q144_ano 
                               ,$this->q144_codvenc 
                               ,$this->q144_receita 
                               ,$this->q144_tipo 
                               ,$this->q144_hist 
                               ,$this->q144_diavenc 
                               ,$this->q144_valor 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configurações de ISSQN Variável ($this->q144_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configurações de ISSQN Variável já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configurações de ISSQN Variável ($this->q144_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->q144_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,20952,'$this->q144_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3774,20952,'','".pg_result($resaco,0,'q144_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20953,'','".pg_result($resaco,0,'q144_ano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20954,'','".pg_result($resaco,0,'q144_codvenc')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20955,'','".pg_result($resaco,0,'q144_receita')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20958,'','".pg_result($resaco,0,'q144_tipo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20957,'','".pg_result($resaco,0,'q144_hist')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20956,'','".pg_result($resaco,0,'q144_diavenc')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20959,'','".pg_result($resaco,0,'q144_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q144_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update confvencissqnvariavel set ";
     $virgula = "";
     if(trim($this->q144_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_sequencial"])){ 
        if(trim($this->q144_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_sequencial"])){ 
           $this->q144_sequencial = "0" ; 
        } 
       $sql  .= $virgula." q144_sequencial = $this->q144_sequencial ";
       $virgula = ",";
       if(trim($this->q144_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q144_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_ano"])){ 
        if(trim($this->q144_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_ano"])){ 
           $this->q144_ano = "0" ; 
        } 
       $sql  .= $virgula." q144_ano = $this->q144_ano ";
       $virgula = ",";
       if(trim($this->q144_ano) == null ){ 
         $this->erro_sql = " Campo Ano Competência nao Informado.";
         $this->erro_campo = "q144_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_codvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_codvenc"])){ 
        if(trim($this->q144_codvenc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_codvenc"])){ 
           $this->q144_codvenc = "0" ; 
        } 
       $sql  .= $virgula." q144_codvenc = $this->q144_codvenc ";
       $virgula = ",";
       if(trim($this->q144_codvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "q144_codvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_receita"])){ 
        if(trim($this->q144_receita)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_receita"])){ 
           $this->q144_receita = "0" ; 
        } 
       $sql  .= $virgula." q144_receita = $this->q144_receita ";
       $virgula = ",";
       if(trim($this->q144_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "q144_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_tipo"])){ 
        if(trim($this->q144_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_tipo"])){ 
           $this->q144_tipo = "0" ; 
        } 
       $sql  .= $virgula." q144_tipo = $this->q144_tipo ";
       $virgula = ",";
       if(trim($this->q144_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "q144_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_hist"])){ 
        if(trim($this->q144_hist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_hist"])){ 
           $this->q144_hist = "0" ; 
        } 
       $sql  .= $virgula." q144_hist = $this->q144_hist ";
       $virgula = ",";
       if(trim($this->q144_hist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "q144_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_diavenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_diavenc"])){ 
        if(trim($this->q144_diavenc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_diavenc"])){ 
           $this->q144_diavenc = "1" ;
        } 
       $sql  .= $virgula." q144_diavenc = $this->q144_diavenc ";
       $virgula = ",";
       if(trim($this->q144_diavenc) == null ){ 
         $this->erro_sql = " Campo Dia do Vencimento nao Informado.";
         $this->erro_campo = "q144_diavenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if ($this->q144_diavenc < 1 || $this->q144_diavenc > 31) {
         $this->erro_sql    = " Campo Dia Vencimento não pode ser maior ou menor que o período do mês.";
         $this->erro_campo  = "q144_diavenc";
         $this->erro_banco  = "";
         $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q144_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q144_valor"])){ 
        if(trim($this->q144_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q144_valor"])){ 
           $this->q144_valor = "0" ; 
        } 
       $sql  .= $virgula." q144_valor = $this->q144_valor ";
       $virgula = ",";
     }
     $sql .= " where  q144_sequencial = $this->q144_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->q144_sequencial));
     if($this->numrows>0){       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,20952,'$this->q144_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_sequencial"]))
         $resac = db_query("insert into db_acount values($acount,3774,20952,'".pg_result($resaco,0,'q144_sequencial')."','$this->q144_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_ano"]))
         $resac = db_query("insert into db_acount values($acount,3774,20953,'".pg_result($resaco,0,'q144_ano')."','$this->q144_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_codvenc"]))
         $resac = db_query("insert into db_acount values($acount,3774,20954,'".pg_result($resaco,0,'q144_codvenc')."','$this->q144_codvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_receita"]))
         $resac = db_query("insert into db_acount values($acount,3774,20955,'".pg_result($resaco,0,'q144_receita')."','$this->q144_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_tipo"]))
         $resac = db_query("insert into db_acount values($acount,3774,20958,'".pg_result($resaco,0,'q144_tipo')."','$this->q144_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_hist"]))
         $resac = db_query("insert into db_acount values($acount,3774,20957,'".pg_result($resaco,0,'q144_hist')."','$this->q144_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_diavenc"]))
         $resac = db_query("insert into db_acount values($acount,3774,20956,'".pg_result($resaco,0,'q144_diavenc')."','$this->q144_diavenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["q144_valor"]))
         $resac = db_query("insert into db_acount values($acount,3774,20959,'".pg_result($resaco,0,'q144_valor')."','$this->q144_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações de ISSQN Variável nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configurações de ISSQN Variável nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q144_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->q144_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,20952,'$this->q144_sequencial','E')");
       $resac = db_query("insert into db_acount values($acount,3774,20952,'','".pg_result($resaco,0,'q144_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20953,'','".pg_result($resaco,0,'q144_ano')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20954,'','".pg_result($resaco,0,'q144_codvenc')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20955,'','".pg_result($resaco,0,'q144_receita')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20958,'','".pg_result($resaco,0,'q144_tipo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20957,'','".pg_result($resaco,0,'q144_hist')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20956,'','".pg_result($resaco,0,'q144_diavenc')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3774,20959,'','".pg_result($resaco,0,'q144_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from confvencissqnvariavel
                    where ";
     $sql2 = "";
      if($this->q144_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " q144_sequencial = $this->q144_sequencial ";
}
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações de ISSQN Variável nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->q144_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configurações de ISSQN Variável nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @db_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q144_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from confvencissqnvariavel ";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = confvencissqnvariavel.q144_codvenc";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = confvencissqnvariavel.q144_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = confvencissqnvariavel.q144_receita";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = confvencissqnvariavel.q144_tipo";
     $sql .= "      inner join histcalc  as a on   a.k01_codigo = cadvencdesc.q92_hist";
     $sql .= "      inner join arretipo  as b on   b.k00_tipo = cadvencdesc.q92_tipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($q144_sequencial!=null ){
         $sql2 .= " where confvencissqnvariavel.q144_sequencial = $q144_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $q144_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from confvencissqnvariavel ";
     $sql2 = "";
     if($dbwhere==""){
       if($q144_sequencial!=null ){
         $sql2 .= " where confvencissqnvariavel.q144_sequencial = $q144_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  /**
   * Retornar o maior dia do vencimento cadastrado
   *
   * @param integer $iCodigoVenc
   * @return integer
   * @throws BusinessException
   * @throws DBException
   */
  public function getISSQNVariavelMaiorVencimento($iCodigoVenc) {

    // Verifica se existe vencimentos cadastrados pega o maior dia
    $sSqlConfVencISSQNVariavel  = "SELECT DATE_PART('day', vencimento.q82_venc)::integer as diavenc                             ";
    $sSqlConfVencISSQNVariavel .= "  FROM (   SELECT q82_codigo, max(q82_venc) AS maiordata                                     ";
    $sSqlConfVencISSQNVariavel .= "             FROM cadvenc                                                                    ";
    $sSqlConfVencISSQNVariavel .= "         GROUP BY q82_codigo                                                                 ";
    $sSqlConfVencISSQNVariavel .= "         ORDER BY q82_codigo ) maiorvencimento                                               ";
    $sSqlConfVencISSQNVariavel .= "       JOIN cadvenc vencimento ON maiorvencimento.maiordata  = vencimento.q82_venc           ";
    $sSqlConfVencISSQNVariavel .= "                              AND maiorvencimento.q82_codigo = vencimento.q82_codigo         ";
    $sSqlConfVencISSQNVariavel .= "       JOIN cadvencdesc descvencimento ON descvencimento.q92_codigo = vencimento.q82_codigo  ";
    $sSqlConfVencISSQNVariavel .= " WHERE vencimento.q82_codigo = {$iCodigoVenc}                                                ";

    $rsConfVencISSQNVariavel = db_query($sSqlConfVencISSQNVariavel);

    if (!$rsConfVencISSQNVariavel) {
      throw new DBException('Erro: '. pg_last_error() );
    }

    if ( pg_num_rows($rsConfVencISSQNVariavel) == 0 ) {
      throw new BusinessException('Nenhum registro encontrado!');
    }

    $oConfVencISSQNVariavel = db_utils::fieldsMemory($rsConfVencISSQNVariavel, 0);
    $iDiaVencimento         = 1;

    if ($oConfVencISSQNVariavel) {
      $iDiaVencimento = $oConfVencISSQNVariavel->diavenc;
    }

    return $iDiaVencimento;
  }

  /**
   * Atualiza as informações dos parâmetros gerais e apenas o dia de vencimento nas rotinas de
   * Parâmetros Gerais e Vencimentos
   *
   * @return $this
   */
  public function atualizaParametrosGeraisVencimento() {

    // Atualiza dia de vencimento para o ISSQN Váriavel
    $oDaoCadVenc    = db_utils::getDao('cadvenc');
    $sSqlCadVenc    = $oDaoCadVenc->sql_query_file($this->q144_codvenc, null, 'q82_venc, q82_parc', 'q82_parc DESC');
    $rsSqlCadVenc   = $oDaoCadVenc->sql_record($sSqlCadVenc);
    $iLinhasCadVenc = $oDaoCadVenc->numrows;
    if ($iLinhasCadVenc > 0) {

      // Altera apenas o dia da data de vencimento conforme informado nos parâmetros do ISSQN Váriavel
      for ($xInd = 0; $xInd < $iLinhasCadVenc; $xInd++) {

        $oCadVenc = db_utils::fieldsMemory($rsSqlCadVenc, $xInd);

        $iDiaVenvimento = (empty($this->q144_diavenc)) ? 1 : $this->q144_diavenc;
        $iDiaVenvimento = str_pad((int) $iDiaVenvimento, 2, '0', STR_PAD_LEFT);
        $iMesVenvimento = date('m', strtotime($oCadVenc->q82_venc));
        $iAnoVenvimento = date('Y', strtotime($oCadVenc->q82_venc));

        // Verifica último dia para os meses que terminam em (28,29,30,31)
        $iUltimoDiaMes = date('t', mktime(0, 0, 0, $iMesVenvimento, '01', $iAnoVenvimento));
        if ($iUltimoDiaMes < $iDiaVenvimento) {
          $iDiaVenvimento = $iUltimoDiaMes;
        }

        $oDaoCadVenc->q82_codigo = $this->q144_codvenc;
        $oDaoCadVenc->q82_parc   = $oCadVenc->q82_parc;
        $oDaoCadVenc->q82_venc   = "{$iAnoVenvimento}-{$iMesVenvimento}-{$iDiaVenvimento}";
        $oDaoCadVenc->alterar($this->q144_codvenc, $oCadVenc->q82_parc);
        if ($oDaoCadVenc->erro_status == '0') {

          $this->erro_sql    = $oDaoCadVenc->erro_sql;
          $this->erro_campo  = $oDaoCadVenc->erro_campo;
          $this->erro_msg    = $oDaoCadVenc->erro_msg;
          $this->erro_banco  = $oDaoCadVenc->erro_banco;
          $this->erro_status = '0';

          return false;
        }
      }
    }

    // Atualiza parâmetros gerais do ISSQN
    $oDaoParISSQN = db_utils::getDao('parissqn');
    $oDaoParISSQN->q60_receit     = $this->q144_receita;
    $oDaoParISSQN->q60_tipo       = $this->q144_tipo;
    $oDaoParISSQN->q60_codvencvar = $this->q144_codvenc;
    $oDaoParISSQN->q60_histsemmov = $this->q144_hist;
    $oDaoParISSQN->alterarParametro();
    if ($oDaoParISSQN->erro_status == '0') {

      $this->erro_sql    = $oDaoParISSQN->erro_sql;
      $this->erro_campo  = $oDaoParISSQN->erro_campo;
      $this->erro_msg    = $oDaoParISSQN->erro_msg;
      $this->erro_banco  = $oDaoParISSQN->erro_banco;
      $this->erro_status = '0';

      return false;
    }

    return $this;
  }
}