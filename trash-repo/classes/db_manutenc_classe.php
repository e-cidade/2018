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

//MODULO: veiculos.off
//CLASSE DA ENTIDADE manutenc
class cl_manutenc { 
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
   var $u06_manut = 0; 
   var $u06_codvei = 0; 
   var $u06_data_dia = null; 
   var $u06_data_mes = null; 
   var $u06_data_ano = null; 
   var $u06_data = null; 
   var $u06_numcgm = 0; 
   var $u06_mobra = 0; 
   var $u06_pecas = 0; 
   var $u06_descr = null; 
   var $u06_nfisc = null; 
   var $u06_km = 0; 
   var $u06_claser = 0; 
   var $u06_login = null; 
   var $u06_dtalt_dia = null; 
   var $u06_dtalt_mes = null; 
   var $u06_dtalt_ano = null; 
   var $u06_dtalt = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 u06_manut = int4 = Numero da manutencao 
                 u06_codvei = int4 = Codigo do Veiculo 
                 u06_data = date = Data da menutencao 
                 u06_numcgm = int4 = Numero CGM da oficina 
                 u06_mobra = float8 = Valor da mao de obra 
                 u06_pecas = float8 = Valor em pecas 
                 u06_descr = char(    60) = Descricao do servico executado 
                 u06_nfisc = char(    10) = Numero da nota fiscal 
                 u06_km = float8 = Km da manutencao 
                 u06_claser = int4 = Classificacao do servico 
                 u06_login = char(     8) = Login do Usuario 
                 u06_dtalt = date = Data da alteracao da situacao 
                 ";
   //funcao construtor da classe 
   function cl_manutenc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("manutenc"); 
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
       $this->u06_manut = ($this->u06_manut == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_manut"]:$this->u06_manut);
       $this->u06_codvei = ($this->u06_codvei == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_codvei"]:$this->u06_codvei);
       if($this->u06_data == ""){
         $this->u06_data_dia = ($this->u06_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_data_dia"]:$this->u06_data_dia);
         $this->u06_data_mes = ($this->u06_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_data_mes"]:$this->u06_data_mes);
         $this->u06_data_ano = ($this->u06_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_data_ano"]:$this->u06_data_ano);
         if($this->u06_data_dia != ""){
            $this->u06_data = $this->u06_data_ano."-".$this->u06_data_mes."-".$this->u06_data_dia;
         }
       }
       $this->u06_numcgm = ($this->u06_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_numcgm"]:$this->u06_numcgm);
       $this->u06_mobra = ($this->u06_mobra == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_mobra"]:$this->u06_mobra);
       $this->u06_pecas = ($this->u06_pecas == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_pecas"]:$this->u06_pecas);
       $this->u06_descr = ($this->u06_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_descr"]:$this->u06_descr);
       $this->u06_nfisc = ($this->u06_nfisc == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_nfisc"]:$this->u06_nfisc);
       $this->u06_km = ($this->u06_km == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_km"]:$this->u06_km);
       $this->u06_claser = ($this->u06_claser == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_claser"]:$this->u06_claser);
       $this->u06_login = ($this->u06_login == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_login"]:$this->u06_login);
       if($this->u06_dtalt == ""){
         $this->u06_dtalt_dia = ($this->u06_dtalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_dtalt_dia"]:$this->u06_dtalt_dia);
         $this->u06_dtalt_mes = ($this->u06_dtalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_dtalt_mes"]:$this->u06_dtalt_mes);
         $this->u06_dtalt_ano = ($this->u06_dtalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["u06_dtalt_ano"]:$this->u06_dtalt_ano);
         if($this->u06_dtalt_dia != ""){
            $this->u06_dtalt = $this->u06_dtalt_ano."-".$this->u06_dtalt_mes."-".$this->u06_dtalt_dia;
         }
       }
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->u06_manut == null ){ 
       $this->erro_sql = " Campo Numero da manutencao nao Informado.";
       $this->erro_campo = "u06_manut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_codvei == null ){ 
       $this->erro_sql = " Campo Codigo do Veiculo nao Informado.";
       $this->erro_campo = "u06_codvei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_data == null ){ 
       $this->erro_sql = " Campo Data da menutencao nao Informado.";
       $this->erro_campo = "u06_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_numcgm == null ){ 
       $this->erro_sql = " Campo Numero CGM da oficina nao Informado.";
       $this->erro_campo = "u06_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_mobra == null ){ 
       $this->erro_sql = " Campo Valor da mao de obra nao Informado.";
       $this->erro_campo = "u06_mobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_pecas == null ){ 
       $this->erro_sql = " Campo Valor em pecas nao Informado.";
       $this->erro_campo = "u06_pecas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_descr == null ){ 
       $this->erro_sql = " Campo Descricao do servico executado nao Informado.";
       $this->erro_campo = "u06_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_nfisc == null ){ 
       $this->erro_sql = " Campo Numero da nota fiscal nao Informado.";
       $this->erro_campo = "u06_nfisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_km == null ){ 
       $this->erro_sql = " Campo Km da manutencao nao Informado.";
       $this->erro_campo = "u06_km";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_claser == null ){ 
       $this->erro_sql = " Campo Classificacao do servico nao Informado.";
       $this->erro_campo = "u06_claser";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_login == null ){ 
       $this->erro_sql = " Campo Login do Usuario nao Informado.";
       $this->erro_campo = "u06_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u06_dtalt == null ){ 
       $this->erro_sql = " Campo Data da alteracao da situacao nao Informado.";
       $this->erro_campo = "u06_dtalt_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into manutenc(
                                       u06_manut 
                                      ,u06_codvei 
                                      ,u06_data 
                                      ,u06_numcgm 
                                      ,u06_mobra 
                                      ,u06_pecas 
                                      ,u06_descr 
                                      ,u06_nfisc 
                                      ,u06_km 
                                      ,u06_claser 
                                      ,u06_login 
                                      ,u06_dtalt 
                       )
                values (
                                $this->u06_manut 
                               ,$this->u06_codvei 
                               ,".($this->u06_data == "null" || $this->u06_data == ""?"null":"'".$this->u06_data."'")." 
                               ,$this->u06_numcgm 
                               ,$this->u06_mobra 
                               ,$this->u06_pecas 
                               ,'$this->u06_descr' 
                               ,'$this->u06_nfisc' 
                               ,$this->u06_km 
                               ,$this->u06_claser 
                               ,'$this->u06_login' 
                               ,".($this->u06_dtalt == "null" || $this->u06_dtalt == ""?"null":"'".$this->u06_dtalt."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Controle de manutencao de veiculos em oficinas     () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Controle de manutencao de veiculos em oficinas     já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Controle de manutencao de veiculos em oficinas     () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update manutenc set ";
     $virgula = "";
     if(trim($this->u06_manut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_manut"])){ 
       $sql  .= $virgula." u06_manut = $this->u06_manut ";
       $virgula = ",";
       if(trim($this->u06_manut) == null ){ 
         $this->erro_sql = " Campo Numero da manutencao nao Informado.";
         $this->erro_campo = "u06_manut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_codvei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_codvei"])){ 
       $sql  .= $virgula." u06_codvei = $this->u06_codvei ";
       $virgula = ",";
       if(trim($this->u06_codvei) == null ){ 
         $this->erro_sql = " Campo Codigo do Veiculo nao Informado.";
         $this->erro_campo = "u06_codvei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["u06_data_dia"] !="") ){ 
       $sql  .= $virgula." u06_data = '$this->u06_data' ";
       $virgula = ",";
       if(trim($this->u06_data) == null ){ 
         $this->erro_sql = " Campo Data da menutencao nao Informado.";
         $this->erro_campo = "u06_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["u06_data_dia"])){ 
         $sql  .= $virgula." u06_data = null ";
         $virgula = ",";
         if(trim($this->u06_data) == null ){ 
           $this->erro_sql = " Campo Data da menutencao nao Informado.";
           $this->erro_campo = "u06_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->u06_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_numcgm"])){ 
       $sql  .= $virgula." u06_numcgm = $this->u06_numcgm ";
       $virgula = ",";
       if(trim($this->u06_numcgm) == null ){ 
         $this->erro_sql = " Campo Numero CGM da oficina nao Informado.";
         $this->erro_campo = "u06_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_mobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_mobra"])){ 
       $sql  .= $virgula." u06_mobra = $this->u06_mobra ";
       $virgula = ",";
       if(trim($this->u06_mobra) == null ){ 
         $this->erro_sql = " Campo Valor da mao de obra nao Informado.";
         $this->erro_campo = "u06_mobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_pecas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_pecas"])){ 
       $sql  .= $virgula." u06_pecas = $this->u06_pecas ";
       $virgula = ",";
       if(trim($this->u06_pecas) == null ){ 
         $this->erro_sql = " Campo Valor em pecas nao Informado.";
         $this->erro_campo = "u06_pecas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_descr"])){ 
       $sql  .= $virgula." u06_descr = '$this->u06_descr' ";
       $virgula = ",";
       if(trim($this->u06_descr) == null ){ 
         $this->erro_sql = " Campo Descricao do servico executado nao Informado.";
         $this->erro_campo = "u06_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_nfisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_nfisc"])){ 
       $sql  .= $virgula." u06_nfisc = '$this->u06_nfisc' ";
       $virgula = ",";
       if(trim($this->u06_nfisc) == null ){ 
         $this->erro_sql = " Campo Numero da nota fiscal nao Informado.";
         $this->erro_campo = "u06_nfisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_km)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_km"])){ 
       $sql  .= $virgula." u06_km = $this->u06_km ";
       $virgula = ",";
       if(trim($this->u06_km) == null ){ 
         $this->erro_sql = " Campo Km da manutencao nao Informado.";
         $this->erro_campo = "u06_km";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_claser)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_claser"])){ 
       $sql  .= $virgula." u06_claser = $this->u06_claser ";
       $virgula = ",";
       if(trim($this->u06_claser) == null ){ 
         $this->erro_sql = " Campo Classificacao do servico nao Informado.";
         $this->erro_campo = "u06_claser";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_login"])){ 
       $sql  .= $virgula." u06_login = '$this->u06_login' ";
       $virgula = ",";
       if(trim($this->u06_login) == null ){ 
         $this->erro_sql = " Campo Login do Usuario nao Informado.";
         $this->erro_campo = "u06_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u06_dtalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u06_dtalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["u06_dtalt_dia"] !="") ){ 
       $sql  .= $virgula." u06_dtalt = '$this->u06_dtalt' ";
       $virgula = ",";
       if(trim($this->u06_dtalt) == null ){ 
         $this->erro_sql = " Campo Data da alteracao da situacao nao Informado.";
         $this->erro_campo = "u06_dtalt_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["u06_dtalt_dia"])){ 
         $sql  .= $virgula." u06_dtalt = null ";
         $virgula = ",";
         if(trim($this->u06_dtalt) == null ){ 
           $this->erro_sql = " Campo Data da alteracao da situacao nao Informado.";
           $this->erro_campo = "u06_dtalt_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de manutencao de veiculos em oficinas     nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de manutencao de veiculos em oficinas     nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from manutenc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de manutencao de veiculos em oficinas     nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de manutencao de veiculos em oficinas     nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:manutenc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>