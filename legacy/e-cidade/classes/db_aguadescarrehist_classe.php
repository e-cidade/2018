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

//MODULO: agua
//CLASSE DA ENTIDADE aguadescarrehist
class cl_aguadescarrehist { 
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
   var $x36_numpre = 0; 
   var $x36_numpar = 0; 
   var $x36_hist = 0; 
   var $x36_dtoper_dia = null; 
   var $x36_dtoper_mes = null; 
   var $x36_dtoper_ano = null; 
   var $x36_dtoper = null; 
   var $x36_hora = null; 
   var $x36_id_usuario = 0; 
   var $x36_histtxt = null; 
   var $x36_limithist_dia = null; 
   var $x36_limithist_mes = null; 
   var $x36_limithist_ano = null; 
   var $x36_limithist = null; 
   var $x36_idhist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x36_numpre = int4 = Numpre 
                 x36_numpar = int4 = Numpar 
                 x36_hist = int4 = Histórico 
                 x36_dtoper = date = Data Operação 
                 x36_hora = char(5) = Hora do Cadastro 
                 x36_id_usuario = int4 = Código Usuário 
                 x36_histtxt = text = Texto para Observação 
                 x36_limithist = date = Data Limite da justificativa 
                 x36_idhist = int4 = Sequencia 
                 ";
   //funcao construtor da classe 
   function cl_aguadescarrehist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguadescarrehist"); 
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
       $this->x36_numpre = ($this->x36_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_numpre"]:$this->x36_numpre);
       $this->x36_numpar = ($this->x36_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_numpar"]:$this->x36_numpar);
       $this->x36_hist = ($this->x36_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_hist"]:$this->x36_hist);
       if($this->x36_dtoper == ""){
         $this->x36_dtoper_dia = @$GLOBALS["HTTP_POST_VARS"]["x36_dtoper_dia"];
         $this->x36_dtoper_mes = @$GLOBALS["HTTP_POST_VARS"]["x36_dtoper_mes"];
         $this->x36_dtoper_ano = @$GLOBALS["HTTP_POST_VARS"]["x36_dtoper_ano"];
         if($this->x36_dtoper_dia != ""){
            $this->x36_dtoper = $this->x36_dtoper_ano."-".$this->x36_dtoper_mes."-".$this->x36_dtoper_dia;
         }
       }
       $this->x36_hora = ($this->x36_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_hora"]:$this->x36_hora);
       $this->x36_id_usuario = ($this->x36_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_id_usuario"]:$this->x36_id_usuario);
       $this->x36_histtxt = ($this->x36_histtxt == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_histtxt"]:$this->x36_histtxt);
       if($this->x36_limithist == ""){
         $this->x36_limithist_dia = @$GLOBALS["HTTP_POST_VARS"]["x36_limithist_dia"];
         $this->x36_limithist_mes = @$GLOBALS["HTTP_POST_VARS"]["x36_limithist_mes"];
         $this->x36_limithist_ano = @$GLOBALS["HTTP_POST_VARS"]["x36_limithist_ano"];
         if($this->x36_limithist_dia != ""){
            $this->x36_limithist = $this->x36_limithist_ano."-".$this->x36_limithist_mes."-".$this->x36_limithist_dia;
         }
       }
       $this->x36_idhist = ($this->x36_idhist == ""?@$GLOBALS["HTTP_POST_VARS"]["x36_idhist"]:$this->x36_idhist);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->x36_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "x36_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "x36_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_hist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "x36_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_dtoper == null ){ 
       $this->erro_sql = " Campo Data Operação nao Informado.";
       $this->erro_campo = "x36_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_hora == null ){ 
       $this->erro_sql = " Campo Hora do Cadastro nao Informado.";
       $this->erro_campo = "x36_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_id_usuario == null ){ 
       $this->erro_sql = " Campo Código Usuário nao Informado.";
       $this->erro_campo = "x36_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_histtxt == null ){ 
       $this->erro_sql = " Campo Texto para Observação nao Informado.";
       $this->erro_campo = "x36_histtxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x36_limithist == null ){ 
       $this->x36_limithist = "null";
     }
     if($this->x36_idhist == null ){ 
       $this->erro_sql = " Campo Sequencia nao Informado.";
       $this->erro_campo = "x36_idhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into aguadescarrehist(
                                       x36_numpre 
                                      ,x36_numpar 
                                      ,x36_hist 
                                      ,x36_dtoper 
                                      ,x36_hora 
                                      ,x36_id_usuario 
                                      ,x36_histtxt 
                                      ,x36_limithist 
                                      ,x36_idhist 
                       )
                values (
                                $this->x36_numpre 
                               ,$this->x36_numpar 
                               ,$this->x36_hist 
                               ,".($this->x36_dtoper == "null" || $this->x36_dtoper == ""?"null":"'".$this->x36_dtoper."'")." 
                               ,'$this->x36_hora' 
                               ,$this->x36_id_usuario 
                               ,'$this->x36_histtxt' 
                               ,".($this->x36_limithist == "null" || $this->x36_limithist == ""?"null":"'".$this->x36_limithist."'")." 
                               ,$this->x36_idhist 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguadescarrehist () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguadescarrehist já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguadescarrehist () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update aguadescarrehist set ";
     $virgula = "";
     if(trim($this->x36_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_numpre"])){ 
        if(trim($this->x36_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x36_numpre"])){ 
           $this->x36_numpre = "0" ; 
        } 
       $sql  .= $virgula." x36_numpre = $this->x36_numpre ";
       $virgula = ",";
       if(trim($this->x36_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "x36_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_numpar"])){ 
        if(trim($this->x36_numpar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x36_numpar"])){ 
           $this->x36_numpar = "0" ; 
        } 
       $sql  .= $virgula." x36_numpar = $this->x36_numpar ";
       $virgula = ",";
       if(trim($this->x36_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "x36_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_hist"])){ 
        if(trim($this->x36_hist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x36_hist"])){ 
           $this->x36_hist = "0" ; 
        } 
       $sql  .= $virgula." x36_hist = $this->x36_hist ";
       $virgula = ",";
       if(trim($this->x36_hist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "x36_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x36_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." x36_dtoper = '$this->x36_dtoper' ";
       $virgula = ",";
       if(trim($this->x36_dtoper) == null ){ 
         $this->erro_sql = " Campo Data Operação nao Informado.";
         $this->erro_campo = "x36_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x36_dtoper_dia"])){ 
         $sql  .= $virgula." x36_dtoper = null ";
         $virgula = ",";
         if(trim($this->x36_dtoper) == null ){ 
           $this->erro_sql = " Campo Data Operação nao Informado.";
           $this->erro_campo = "x36_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x36_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_hora"])){ 
       $sql  .= $virgula." x36_hora = '$this->x36_hora' ";
       $virgula = ",";
       if(trim($this->x36_hora) == null ){ 
         $this->erro_sql = " Campo Hora do Cadastro nao Informado.";
         $this->erro_campo = "x36_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_id_usuario"])){ 
        if(trim($this->x36_id_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x36_id_usuario"])){ 
           $this->x36_id_usuario = "0" ; 
        } 
       $sql  .= $virgula." x36_id_usuario = $this->x36_id_usuario ";
       $virgula = ",";
       if(trim($this->x36_id_usuario) == null ){ 
         $this->erro_sql = " Campo Código Usuário nao Informado.";
         $this->erro_campo = "x36_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_histtxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_histtxt"])){ 
       $sql  .= $virgula." x36_histtxt = '$this->x36_histtxt' ";
       $virgula = ",";
       if(trim($this->x36_histtxt) == null ){ 
         $this->erro_sql = " Campo Texto para Observação nao Informado.";
         $this->erro_campo = "x36_histtxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x36_limithist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_limithist_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x36_limithist_dia"] !="") ){ 
       $sql  .= $virgula." x36_limithist = '$this->x36_limithist' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x36_limithist_dia"])){ 
         $sql  .= $virgula." x36_limithist = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x36_idhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x36_idhist"])){ 
        if(trim($this->x36_idhist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x36_idhist"])){ 
           $this->x36_idhist = "0" ; 
        } 
       $sql  .= $virgula." x36_idhist = $this->x36_idhist ";
       $virgula = ",";
       if(trim($this->x36_idhist) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "x36_idhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where oid = $oid ";
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguadescarrehist nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguadescarrehist nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ) { 
     $this->atualizacampos(true);
     $sql = " delete from aguadescarrehist
                    where ";
     $sql2 = "";
     $sql2 = "oid = $oid";
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguadescarrehist nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguadescarrehist nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
   function sql_query ( $oid = null,$campos="aguadescarrehist.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguadescarrehist ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguadescarrehist.x36_hist";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguadescarrehist.x36_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where aguadescarrehist.oid = $oid";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguadescarrehist ";
     $sql2 = "";
     if($dbwhere==""){
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