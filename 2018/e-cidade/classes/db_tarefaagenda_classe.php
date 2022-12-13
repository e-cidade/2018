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

//MODULO: Atendimento
//CLASSE DA ENTIDADE tarefaagenda
class cl_tarefaagenda { 
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
   var $at77_sequen = 0; 
   var $at77_tarefa = 0; 
   var $at77_id_usuario = 0; 
   var $at77_usuenvolvido = 0; 
   var $at77_datainclusao_dia = null; 
   var $at77_datainclusao_mes = null; 
   var $at77_datainclusao_ano = null; 
   var $at77_datainclusao = null; 
   var $at77_dataagenda_dia = null; 
   var $at77_dataagenda_mes = null; 
   var $at77_dataagenda_ano = null; 
   var $at77_dataagenda = null; 
   var $at77_observacao = null; 
   var $at77_datavalidade_dia = null; 
   var $at77_datavalidade_mes = null; 
   var $at77_datavalidade_ano = null; 
   var $at77_datavalidade = null; 
   var $at77_hora = null; 
   var $at77_cliente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at77_sequen = int4 = Sequencial 
                 at77_tarefa = int4 = Tarefa 
                 at77_id_usuario = int4 = Cod. Usuário 
                 at77_usuenvolvido = int4 = Cod. Usuário 
                 at77_datainclusao = date = Data Inclusão 
                 at77_dataagenda = date = Data Agendada 
                 at77_observacao = text = Observação 
                 at77_datavalidade = date = Validade 
                 at77_hora = char(5) = Hora 
                 at77_cliente = int4 = Codigo do Cliente 
                 ";
   //funcao construtor da classe 
   function cl_tarefaagenda() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefaagenda"); 
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
       $this->at77_sequen = ($this->at77_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_sequen"]:$this->at77_sequen);
       $this->at77_tarefa = ($this->at77_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_tarefa"]:$this->at77_tarefa);
       $this->at77_id_usuario = ($this->at77_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_id_usuario"]:$this->at77_id_usuario);
       $this->at77_usuenvolvido = ($this->at77_usuenvolvido == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_usuenvolvido"]:$this->at77_usuenvolvido);
       if($this->at77_datainclusao == ""){
         $this->at77_datainclusao_dia = ($this->at77_datainclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_dia"]:$this->at77_datainclusao_dia);
         $this->at77_datainclusao_mes = ($this->at77_datainclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_mes"]:$this->at77_datainclusao_mes);
         $this->at77_datainclusao_ano = ($this->at77_datainclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_ano"]:$this->at77_datainclusao_ano);
         if($this->at77_datainclusao_dia != ""){
            $this->at77_datainclusao = $this->at77_datainclusao_ano."-".$this->at77_datainclusao_mes."-".$this->at77_datainclusao_dia;
         }
       }
       if($this->at77_dataagenda == ""){
         $this->at77_dataagenda_dia = ($this->at77_dataagenda_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_dia"]:$this->at77_dataagenda_dia);
         $this->at77_dataagenda_mes = ($this->at77_dataagenda_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_mes"]:$this->at77_dataagenda_mes);
         $this->at77_dataagenda_ano = ($this->at77_dataagenda_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_ano"]:$this->at77_dataagenda_ano);
         if($this->at77_dataagenda_dia != ""){
            $this->at77_dataagenda = $this->at77_dataagenda_ano."-".$this->at77_dataagenda_mes."-".$this->at77_dataagenda_dia;
         }
       }
       $this->at77_observacao = ($this->at77_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_observacao"]:$this->at77_observacao);
       if($this->at77_datavalidade == ""){
         $this->at77_datavalidade_dia = ($this->at77_datavalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_dia"]:$this->at77_datavalidade_dia);
         $this->at77_datavalidade_mes = ($this->at77_datavalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_mes"]:$this->at77_datavalidade_mes);
         $this->at77_datavalidade_ano = ($this->at77_datavalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_ano"]:$this->at77_datavalidade_ano);
         if($this->at77_datavalidade_dia != ""){
            $this->at77_datavalidade = $this->at77_datavalidade_ano."-".$this->at77_datavalidade_mes."-".$this->at77_datavalidade_dia;
         }
       }
       $this->at77_hora = ($this->at77_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_hora"]:$this->at77_hora);
       $this->at77_cliente = ($this->at77_cliente == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_cliente"]:$this->at77_cliente);
     }else{
       $this->at77_sequen = ($this->at77_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["at77_sequen"]:$this->at77_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($at77_sequen){ 
      $this->atualizacampos();
     if($this->at77_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at77_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at77_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_usuenvolvido == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at77_usuenvolvido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_datainclusao == null ){ 
       $this->erro_sql = " Campo Data Inclusão nao Informado.";
       $this->erro_campo = "at77_datainclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_dataagenda == null ){ 
       $this->erro_sql = " Campo Data Agendada nao Informado.";
       $this->erro_campo = "at77_dataagenda_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_observacao == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "at77_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_datavalidade == null ){ 
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "at77_datavalidade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "at77_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at77_cliente == null ){ 
       $this->erro_sql = " Campo Codigo do Cliente nao Informado.";
       $this->erro_campo = "at77_cliente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at77_sequen == "" || $at77_sequen == null ){
       $result = db_query("select nextval('tarefaagenda_at77_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefaagenda_at77_sequen_seq do campo: at77_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at77_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefaagenda_at77_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $at77_sequen)){
         $this->erro_sql = " Campo at77_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at77_sequen = $at77_sequen; 
       }
     }
     if(($this->at77_sequen == null) || ($this->at77_sequen == "") ){ 
       $this->erro_sql = " Campo at77_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefaagenda(
                                       at77_sequen 
                                      ,at77_tarefa 
                                      ,at77_id_usuario 
                                      ,at77_usuenvolvido 
                                      ,at77_datainclusao 
                                      ,at77_dataagenda 
                                      ,at77_observacao 
                                      ,at77_datavalidade 
                                      ,at77_hora 
                                      ,at77_cliente 
                       )
                values (
                                $this->at77_sequen 
                               ,$this->at77_tarefa 
                               ,$this->at77_id_usuario 
                               ,$this->at77_usuenvolvido 
                               ,".($this->at77_datainclusao == "null" || $this->at77_datainclusao == ""?"null":"'".$this->at77_datainclusao."'")." 
                               ,".($this->at77_dataagenda == "null" || $this->at77_dataagenda == ""?"null":"'".$this->at77_dataagenda."'")." 
                               ,'$this->at77_observacao' 
                               ,".($this->at77_datavalidade == "null" || $this->at77_datavalidade == ""?"null":"'".$this->at77_datavalidade."'")." 
                               ,'$this->at77_hora' 
                               ,$this->at77_cliente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda Pessoal ($this->at77_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda Pessoal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda Pessoal ($this->at77_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at77_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at77_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14426,'$this->at77_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,2544,14426,'','".AddSlashes(pg_result($resaco,0,'at77_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14427,'','".AddSlashes(pg_result($resaco,0,'at77_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14428,'','".AddSlashes(pg_result($resaco,0,'at77_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14429,'','".AddSlashes(pg_result($resaco,0,'at77_usuenvolvido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14430,'','".AddSlashes(pg_result($resaco,0,'at77_datainclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14431,'','".AddSlashes(pg_result($resaco,0,'at77_dataagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14432,'','".AddSlashes(pg_result($resaco,0,'at77_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14433,'','".AddSlashes(pg_result($resaco,0,'at77_datavalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,14434,'','".AddSlashes(pg_result($resaco,0,'at77_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2544,16226,'','".AddSlashes(pg_result($resaco,0,'at77_cliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at77_sequen=null) { 
      $this->atualizacampos();
     $sql = " update tarefaagenda set ";
     $virgula = "";
     if(trim($this->at77_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_sequen"])){ 
       $sql  .= $virgula." at77_sequen = $this->at77_sequen ";
       $virgula = ",";
       if(trim($this->at77_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at77_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_tarefa"])){ 
       $sql  .= $virgula." at77_tarefa = $this->at77_tarefa ";
       $virgula = ",";
       if(trim($this->at77_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at77_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_id_usuario"])){ 
       $sql  .= $virgula." at77_id_usuario = $this->at77_id_usuario ";
       $virgula = ",";
       if(trim($this->at77_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at77_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_usuenvolvido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_usuenvolvido"])){ 
       $sql  .= $virgula." at77_usuenvolvido = $this->at77_usuenvolvido ";
       $virgula = ",";
       if(trim($this->at77_usuenvolvido) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at77_usuenvolvido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_datainclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_dia"] !="") ){ 
       $sql  .= $virgula." at77_datainclusao = '$this->at77_datainclusao' ";
       $virgula = ",";
       if(trim($this->at77_datainclusao) == null ){ 
         $this->erro_sql = " Campo Data Inclusão nao Informado.";
         $this->erro_campo = "at77_datainclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at77_datainclusao_dia"])){ 
         $sql  .= $virgula." at77_datainclusao = null ";
         $virgula = ",";
         if(trim($this->at77_datainclusao) == null ){ 
           $this->erro_sql = " Campo Data Inclusão nao Informado.";
           $this->erro_campo = "at77_datainclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at77_dataagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_dia"] !="") ){ 
       $sql  .= $virgula." at77_dataagenda = '$this->at77_dataagenda' ";
       $virgula = ",";
       if(trim($this->at77_dataagenda) == null ){ 
         $this->erro_sql = " Campo Data Agendada nao Informado.";
         $this->erro_campo = "at77_dataagenda_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at77_dataagenda_dia"])){ 
         $sql  .= $virgula." at77_dataagenda = null ";
         $virgula = ",";
         if(trim($this->at77_dataagenda) == null ){ 
           $this->erro_sql = " Campo Data Agendada nao Informado.";
           $this->erro_campo = "at77_dataagenda_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at77_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_observacao"])){ 
       $sql  .= $virgula." at77_observacao = '$this->at77_observacao' ";
       $virgula = ",";
       if(trim($this->at77_observacao) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "at77_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_datavalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_dia"] !="") ){ 
       $sql  .= $virgula." at77_datavalidade = '$this->at77_datavalidade' ";
       $virgula = ",";
       if(trim($this->at77_datavalidade) == null ){ 
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "at77_datavalidade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at77_datavalidade_dia"])){ 
         $sql  .= $virgula." at77_datavalidade = null ";
         $virgula = ",";
         if(trim($this->at77_datavalidade) == null ){ 
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "at77_datavalidade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at77_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_hora"])){ 
       $sql  .= $virgula." at77_hora = '$this->at77_hora' ";
       $virgula = ",";
       if(trim($this->at77_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "at77_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at77_cliente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at77_cliente"])){ 
       $sql  .= $virgula." at77_cliente = $this->at77_cliente ";
       $virgula = ",";
       if(trim($this->at77_cliente) == null ){ 
         $this->erro_sql = " Campo Codigo do Cliente nao Informado.";
         $this->erro_campo = "at77_cliente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at77_sequen!=null){
       $sql .= " at77_sequen = $at77_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at77_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14426,'$this->at77_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_sequen"]) || $this->at77_sequen != "")
           $resac = db_query("insert into db_acount values($acount,2544,14426,'".AddSlashes(pg_result($resaco,$conresaco,'at77_sequen'))."','$this->at77_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_tarefa"]) || $this->at77_tarefa != "")
           $resac = db_query("insert into db_acount values($acount,2544,14427,'".AddSlashes(pg_result($resaco,$conresaco,'at77_tarefa'))."','$this->at77_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_id_usuario"]) || $this->at77_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2544,14428,'".AddSlashes(pg_result($resaco,$conresaco,'at77_id_usuario'))."','$this->at77_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_usuenvolvido"]) || $this->at77_usuenvolvido != "")
           $resac = db_query("insert into db_acount values($acount,2544,14429,'".AddSlashes(pg_result($resaco,$conresaco,'at77_usuenvolvido'))."','$this->at77_usuenvolvido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_datainclusao"]) || $this->at77_datainclusao != "")
           $resac = db_query("insert into db_acount values($acount,2544,14430,'".AddSlashes(pg_result($resaco,$conresaco,'at77_datainclusao'))."','$this->at77_datainclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_dataagenda"]) || $this->at77_dataagenda != "")
           $resac = db_query("insert into db_acount values($acount,2544,14431,'".AddSlashes(pg_result($resaco,$conresaco,'at77_dataagenda'))."','$this->at77_dataagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_observacao"]) || $this->at77_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2544,14432,'".AddSlashes(pg_result($resaco,$conresaco,'at77_observacao'))."','$this->at77_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_datavalidade"]) || $this->at77_datavalidade != "")
           $resac = db_query("insert into db_acount values($acount,2544,14433,'".AddSlashes(pg_result($resaco,$conresaco,'at77_datavalidade'))."','$this->at77_datavalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_hora"]) || $this->at77_hora != "")
           $resac = db_query("insert into db_acount values($acount,2544,14434,'".AddSlashes(pg_result($resaco,$conresaco,'at77_hora'))."','$this->at77_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at77_cliente"]) || $this->at77_cliente != "")
           $resac = db_query("insert into db_acount values($acount,2544,16226,'".AddSlashes(pg_result($resaco,$conresaco,'at77_cliente'))."','$this->at77_cliente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda Pessoal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at77_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda Pessoal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at77_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at77_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at77_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at77_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14426,'$at77_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,2544,14426,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14427,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14428,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14429,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_usuenvolvido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14430,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_datainclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14431,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_dataagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14432,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14433,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_datavalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,14434,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2544,16226,'','".AddSlashes(pg_result($resaco,$iresaco,'at77_cliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefaagenda
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at77_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at77_sequen = $at77_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda Pessoal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at77_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda Pessoal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at77_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at77_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefaagenda";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at77_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaagenda ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefaagenda.at77_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at77_sequen!=null ){
         $sql2 .= " where tarefaagenda.at77_sequen = $at77_sequen "; 
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
   function sql_query_file ( $at77_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaagenda ";
     $sql2 = "";
     if($dbwhere==""){
       if($at77_sequen!=null ){
         $sql2 .= " where tarefaagenda.at77_sequen = $at77_sequen "; 
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